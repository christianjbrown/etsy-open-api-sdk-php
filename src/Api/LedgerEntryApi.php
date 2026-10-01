<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonReadApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntryInterface;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntriesTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntryTransformerInterface;

use function is_array;
use function sprintf;

final class LedgerEntryApi implements LedgerEntryApiInterface
{
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private ResponseCacheInterface $entryCache;
    private PaymentAccountLedgerEntriesTransformerInterface $paymentAccountLedgerEntriesTransformer;
    private PaymentAccountLedgerEntryTransformerInterface $paymentAccountLedgerEntryTransformer;
    private JsonReadApiRequestSenderInterface $requestSender;
    private int $shopId;

    public function __construct(JsonReadApiRequestSenderInterface $requestSender, PaymentAccountLedgerEntryTransformerInterface $paymentAccountLedgerEntryTransformer, PaymentAccountLedgerEntriesTransformerInterface $paymentAccountLedgerEntriesTransformer, ResponseCacheInterface $cache, ResponseCacheInterface $entryCache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->paymentAccountLedgerEntryTransformer = $paymentAccountLedgerEntryTransformer;
        $this->paymentAccountLedgerEntriesTransformer = $paymentAccountLedgerEntriesTransformer;
        $this->cache = $cache;
        $this->entryCache = $entryCache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, PaymentAccountLedgerEntryInterface>
     */
    public function getMultiple(?int $minCreated = null, ?int $maxCreated = null, int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%s:%s:%d:%d', $minCreated ?? '', $maxCreated ?? '', $limit, $offset);
        if (!$skipCache) {
            if ($this->cache->has($cacheKey)) {
                /**
                 * @var array<int, PaymentAccountLedgerEntryInterface> $cached
                 */
                $cached = $this->cache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildQuery($minCreated, $maxCreated, $limit, $offset), $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $entries = $this->paymentAccountLedgerEntriesTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache->set($cacheKey, $entries);

        return $entries;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $ledgerEntryId, bool $skipCache = false): PaymentAccountLedgerEntryInterface
    {
        if (!$skipCache) {
            if ($this->entryCache->has((string) $ledgerEntryId)) {
                /**
                 * @var PaymentAccountLedgerEntryInterface $cached
                 */
                $cached = $this->entryCache->get((string) $ledgerEntryId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $ledgerEntryId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $entry = $this->paymentAccountLedgerEntryTransformer->transform($data);
        $this->entryCache->set((string) $ledgerEntryId, $entry);

        return $entry;
    }

    /**
     * @return array<string, string>
     */
    private static function buildQuery(?int $minCreated, ?int $maxCreated, int $limit, int $offset): array
    {
        $query = [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
        if (null !== $minCreated) {
            $query[self::KEY_MIN_CREATED] = (string) $minCreated;
        }
        if (null !== $maxCreated) {
            $query[self::KEY_MAX_CREATED] = (string) $maxCreated;
        }

        return $query;
    }
}
