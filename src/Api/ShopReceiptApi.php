<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\CreateReceiptShipmentRequestInterface;
use ChristianBrown\Etsy\Model\ReceiptInterface;
use ChristianBrown\Etsy\Model\ReceiptPageInterface;
use ChristianBrown\Etsy\Model\UpdateShopReceiptRequestInterface;
use ChristianBrown\Etsy\Serializer\CreateReceiptShipmentRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopReceiptRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptPageTransformerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptTransformerInterface;

use function array_filter;
use function array_map;
use function implode;
use function is_array;
use function sprintf;

final class ShopReceiptApi implements ShopReceiptApiInterface
{
    private ResponseCacheInterface $cache;
    private CreateReceiptShipmentRequestSerializerInterface $createReceiptShipmentRequestSerializer;
    private CredentialsInterface $credentials;
    private ResponseCacheInterface $pageCache;
    private ResponseCacheInterface $receiptCache;
    private ReceiptPageTransformerInterface $receiptPageTransformer;
    private ReceiptsTransformerInterface $receiptsTransformer;
    private ReceiptTransformerInterface $receiptTransformer;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UpdateShopReceiptRequestSerializerInterface $updateShopReceiptRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ReceiptTransformerInterface $receiptTransformer, ReceiptsTransformerInterface $receiptsTransformer, ReceiptPageTransformerInterface $receiptPageTransformer, CreateReceiptShipmentRequestSerializerInterface $createReceiptShipmentRequestSerializer, UpdateShopReceiptRequestSerializerInterface $updateShopReceiptRequestSerializer, ResponseCacheInterface $cache, ResponseCacheInterface $pageCache, ResponseCacheInterface $receiptCache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->receiptTransformer = $receiptTransformer;
        $this->receiptsTransformer = $receiptsTransformer;
        $this->receiptPageTransformer = $receiptPageTransformer;
        $this->createReceiptShipmentRequestSerializer = $createReceiptShipmentRequestSerializer;
        $this->updateShopReceiptRequestSerializer = $updateShopReceiptRequestSerializer;
        $this->cache = $cache;
        $this->pageCache = $pageCache;
        $this->receiptCache = $receiptCache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function createReceiptShipment(int $receiptId, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest, bool $legacy = false): ReceiptInterface
    {
        $url = sprintf(self::API_URL_TRACKING_SPRINTF, $this->shopId, $receiptId);
        $data = $this->requestSender->post($url, self::buildLegacyQuery($legacy), $this->credentials->toHeaders(), $this->createReceiptShipmentRequestSerializer->serialize($createReceiptShipmentRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $receipt = $this->receiptTransformer->transform($data);
        $this->cache->clear();
        $this->pageCache->clear();
        $this->receiptCache->set((string) $receiptId, $receipt);

        return $receipt;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ReceiptInterface>
     */
    public function getMultiple(int $limit = 100, int $offset = 0, bool $skipCache = false, ?int $minCreated = null, ?int $maxCreated = null, ?int $minLastModified = null, ?int $maxLastModified = null, ?string $sortOn = null, ?string $sortOrder = null, ?bool $wasPaid = null, ?bool $wasShipped = null, ?bool $wasDelivered = null, ?bool $wasCanceled = null): array
    {
        $cacheKey = self::buildCacheKey($limit, $offset, $minCreated, $maxCreated, $minLastModified, $maxLastModified, $sortOn, $sortOrder, $wasPaid, $wasShipped, $wasDelivered, $wasCanceled);
        if (!$skipCache) {
            if ($this->cache->has($cacheKey)) {
                /**
                 * @var array<int, ReceiptInterface> $cached
                 */
                $cached = $this->cache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildQuery($limit, $offset, $minCreated, $maxCreated, $minLastModified, $maxLastModified, $sortOn, $sortOrder, $wasPaid, $wasShipped, $wasDelivered, $wasCanceled), $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $receipts = $this->receiptsTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache->set($cacheKey, $receipts);

        return $receipts;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $receiptId, bool $skipCache = false): ReceiptInterface
    {
        if (!$skipCache) {
            if ($this->receiptCache->has((string) $receiptId)) {
                /**
                 * @var ReceiptInterface $cached
                 */
                $cached = $this->receiptCache->get((string) $receiptId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $receiptId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $receipt = $this->receiptTransformer->transform($data);
        $this->receiptCache->set((string) $receiptId, $receipt);

        return $receipt;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getPage(int $limit = 100, int $offset = 0, bool $skipCache = false, ?int $minCreated = null, ?int $maxCreated = null, ?int $minLastModified = null, ?int $maxLastModified = null, ?string $sortOn = null, ?string $sortOrder = null, ?bool $wasPaid = null, ?bool $wasShipped = null, ?bool $wasDelivered = null, ?bool $wasCanceled = null): ReceiptPageInterface
    {
        $cacheKey = self::buildCacheKey($limit, $offset, $minCreated, $maxCreated, $minLastModified, $maxLastModified, $sortOn, $sortOrder, $wasPaid, $wasShipped, $wasDelivered, $wasCanceled);
        if (!$skipCache) {
            if ($this->pageCache->has($cacheKey)) {
                /**
                 * @var ReceiptPageInterface $cached
                 */
                $cached = $this->pageCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildQuery($limit, $offset, $minCreated, $maxCreated, $minLastModified, $maxLastModified, $sortOn, $sortOrder, $wasPaid, $wasShipped, $wasDelivered, $wasCanceled), $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $page = $this->receiptPageTransformer->transform($data);
        $this->pageCache->set($cacheKey, $page);

        return $page;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function updateShopReceipt(int $receiptId, UpdateShopReceiptRequestInterface $updateShopReceiptRequest, bool $legacy = false): ReceiptInterface
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $receiptId);
        $data = $this->requestSender->putForm($url, self::buildLegacyQuery($legacy), $this->credentials->toHeaders(), $this->updateShopReceiptRequestSerializer->serialize($updateShopReceiptRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $receipt = $this->receiptTransformer->transform($data);
        $this->cache->clear();
        $this->pageCache->clear();
        $this->receiptCache->set((string) $receiptId, $receipt);

        return $receipt;
    }

    private static function buildCacheKey(int $limit, int $offset, ?int $minCreated, ?int $maxCreated, ?int $minLastModified, ?int $maxLastModified, ?string $sortOn, ?string $sortOrder, ?bool $wasPaid, ?bool $wasShipped, ?bool $wasDelivered, ?bool $wasCanceled): string
    {
        $parts = [
            (string) $limit,
            (string) $offset,
            self::encodeOptionalInt($minCreated),
            self::encodeOptionalInt($maxCreated),
            self::encodeOptionalInt($minLastModified),
            self::encodeOptionalInt($maxLastModified),
            $sortOn,
            $sortOrder,
            self::encodeOptionalBool($wasPaid),
            self::encodeOptionalBool($wasShipped),
            self::encodeOptionalBool($wasDelivered),
            self::encodeOptionalBool($wasCanceled),
        ];

        return implode(':', array_map(static fn (?string $part): string => $part ?? '', $parts));
    }

    /**
     * @return array<string, string>
     */
    private static function buildLegacyQuery(bool $legacy): array
    {
        if (!$legacy) {
            return [];
        }

        return [self::KEY_LEGACY => 'true'];
    }

    /**
     * @return array<string, string>
     */
    private static function buildQuery(int $limit, int $offset, ?int $minCreated = null, ?int $maxCreated = null, ?int $minLastModified = null, ?int $maxLastModified = null, ?string $sortOn = null, ?string $sortOrder = null, ?bool $wasPaid = null, ?bool $wasShipped = null, ?bool $wasDelivered = null, ?bool $wasCanceled = null): array
    {
        $optional = array_filter(
            [
                self::KEY_MIN_CREATED => self::encodeOptionalInt($minCreated),
                self::KEY_MAX_CREATED => self::encodeOptionalInt($maxCreated),
                self::KEY_MIN_LAST_MODIFIED => self::encodeOptionalInt($minLastModified),
                self::KEY_MAX_LAST_MODIFIED => self::encodeOptionalInt($maxLastModified),
                self::KEY_SORT_ON => $sortOn,
                self::KEY_SORT_ORDER => $sortOrder,
                self::KEY_WAS_PAID => self::encodeOptionalBool($wasPaid),
                self::KEY_WAS_SHIPPED => self::encodeOptionalBool($wasShipped),
                self::KEY_WAS_DELIVERED => self::encodeOptionalBool($wasDelivered),
                self::KEY_WAS_CANCELED => self::encodeOptionalBool($wasCanceled),
            ],
            static fn (?string $value): bool => null !== $value
        );

        return [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ] + $optional;
    }

    private static function encodeOptionalBool(?bool $value): ?string
    {
        if (null === $value) {
            return null;
        }

        return $value ? 'true' : 'false';
    }

    private static function encodeOptionalInt(?int $value): ?string
    {
        if (null === $value) {
            return null;
        }

        return (string) $value;
    }
}
