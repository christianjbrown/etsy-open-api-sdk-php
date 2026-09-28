<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;
use ChristianBrown\Etsy\Model\UpdateListingInventoryRequestInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingInventoryRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryTransformerInterface;

use function sprintf;

final class ListingInventoryApi implements ListingInventoryApiInterface
{
    private ResponseCacheInterface $byListingIdCache;
    private CredentialsInterface $credentials;
    private ListingInventoryProductOfferingTransformerInterface $listingInventoryProductOfferingTransformer;
    private ListingInventoryProductTransformerInterface $listingInventoryProductTransformer;
    private ListingInventoryTransformerInterface $listingInventoryTransformer;
    private ResponseCacheInterface $offeringCache;
    private ResponseCacheInterface $productCache;
    private JsonApiRequestSenderInterface $requestSender;
    private UpdateListingInventoryRequestSerializerInterface $updateListingInventoryRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ListingInventoryTransformerInterface $listingInventoryTransformer, ListingInventoryProductTransformerInterface $listingInventoryProductTransformer, ListingInventoryProductOfferingTransformerInterface $listingInventoryProductOfferingTransformer, UpdateListingInventoryRequestSerializerInterface $updateListingInventoryRequestSerializer, ResponseCacheInterface $byListingIdCache, ResponseCacheInterface $offeringCache, ResponseCacheInterface $productCache, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->listingInventoryTransformer = $listingInventoryTransformer;
        $this->listingInventoryProductTransformer = $listingInventoryProductTransformer;
        $this->listingInventoryProductOfferingTransformer = $listingInventoryProductOfferingTransformer;
        $this->updateListingInventoryRequestSerializer = $updateListingInventoryRequestSerializer;
        $this->byListingIdCache = $byListingIdCache;
        $this->offeringCache = $offeringCache;
        $this->productCache = $productCache;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getByListingId(int $listingId, bool $skipCache = false, ?bool $showDeleted = null, ?string $includes = null): ListingInventoryInterface
    {
        $cacheKey = self::buildByListingIdCacheKey($listingId, $showDeleted, $includes);
        if (!$skipCache) {
            if ($this->byListingIdCache->has($cacheKey)) {
                /**
                 * @var ListingInventoryInterface $cached
                 */
                $cached = $this->byListingIdCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_LISTING_ID_SPRINTF, $listingId);
        $data = $this->requestSender->get($url, self::buildByListingIdQuery($showDeleted, $includes), $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $inventory = $this->listingInventoryTransformer->transform($data);
        $this->byListingIdCache->set($cacheKey, $inventory);

        return $inventory;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOffering(int $listingId, int $productId, int $offeringId, bool $skipCache = false, ?bool $legacy = null): ListingInventoryProductOfferingInterface
    {
        $cacheKey = sprintf('%d:%d:%d:%s', $listingId, $productId, $offeringId, null === $legacy ? '' : (int) $legacy);
        if (!$skipCache) {
            if ($this->offeringCache->has($cacheKey)) {
                /**
                 * @var ListingInventoryProductOfferingInterface $cached
                 */
                $cached = $this->offeringCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_OFFERING_SPRINTF, $listingId, $productId, $offeringId);
        $data = $this->requestSender->get($url, self::buildLegacyQuery($legacy), $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $offering = $this->listingInventoryProductOfferingTransformer->transform($data);
        $this->offeringCache->set($cacheKey, $offering);

        return $offering;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getProduct(int $listingId, int $productId, bool $skipCache = false, ?bool $legacy = null): ListingInventoryProductInterface
    {
        $cacheKey = sprintf('%d:%d:%s', $listingId, $productId, null === $legacy ? '' : (int) $legacy);
        if (!$skipCache) {
            if ($this->productCache->has($cacheKey)) {
                /**
                 * @var ListingInventoryProductInterface $cached
                 */
                $cached = $this->productCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_PRODUCT_SPRINTF, $listingId, $productId);
        $data = $this->requestSender->get($url, self::buildLegacyQuery($legacy), $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $product = $this->listingInventoryProductTransformer->transform($data);
        $this->productCache->set($cacheKey, $product);

        return $product;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function update(int $listingId, UpdateListingInventoryRequestInterface $updateListingInventoryRequest, ?string $maxVariationsSupported = null): ListingInventoryInterface
    {
        $url = sprintf(self::API_URL_BY_LISTING_ID_SPRINTF, $listingId);
        $data = $this->requestSender->put($url, self::buildMaxVariationsQuery($maxVariationsSupported), $this->credentials->toHeaders(), $this->updateListingInventoryRequestSerializer->serialize($updateListingInventoryRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $inventory = $this->listingInventoryTransformer->transform($data);
        $this->byListingIdCache->set(self::buildByListingIdCacheKey($listingId, null, null), $inventory);
        $this->offeringCache->clear();
        $this->productCache->clear();

        return $inventory;
    }

    private static function buildByListingIdCacheKey(int $listingId, ?bool $showDeleted, ?string $includes): string
    {
        return sprintf('%d:%s:%s', $listingId, null === $showDeleted ? '' : (int) $showDeleted, $includes ?? '');
    }

    /**
     * @return array<string, string>
     */
    private static function buildByListingIdQuery(?bool $showDeleted, ?string $includes): array
    {
        $query = [];
        if (null !== $showDeleted) {
            $query[self::KEY_SHOW_DELETED] = $showDeleted ? 'true' : 'false';
        }
        if (null !== $includes) {
            $query[self::KEY_INCLUDES] = $includes;
        }

        return $query;
    }

    /**
     * @return array<string, string>
     */
    private static function buildLegacyQuery(?bool $legacy): array
    {
        if (null === $legacy) {
            return [];
        }

        return [self::KEY_LEGACY => $legacy ? 'true' : 'false'];
    }

    /**
     * @return array<string, string>
     */
    private static function buildMaxVariationsQuery(?string $maxVariationsSupported): array
    {
        if (null === $maxVariationsSupported) {
            return [];
        }

        return [self::KEY_MAX_VARIATIONS_SUPPORTED => $maxVariationsSupported];
    }
}
