<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntryInterface;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntriesTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntryTransformerInterface;

use function is_array;
use function sprintf;

final class LedgerEntryApi implements LedgerEntryApiInterface
{
    /**
     * @var array<string, array<int, PaymentAccountLedgerEntryInterface>>
     */
    private array $cache = [];
    private CredentialsInterface $credentials;

    /**
     * @var array<int, PaymentAccountLedgerEntryInterface>
     */
    private array $entryCache = [];
    private PaymentAccountLedgerEntriesTransformerInterface $paymentAccountLedgerEntriesTransformer;
    private PaymentAccountLedgerEntryTransformerInterface $paymentAccountLedgerEntryTransformer;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;

    public function __construct(JsonApiRequestSenderInterface $requestSender, PaymentAccountLedgerEntryTransformerInterface $paymentAccountLedgerEntryTransformer, PaymentAccountLedgerEntriesTransformerInterface $paymentAccountLedgerEntriesTransformer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->paymentAccountLedgerEntryTransformer = $paymentAccountLedgerEntryTransformer;
        $this->paymentAccountLedgerEntriesTransformer = $paymentAccountLedgerEntriesTransformer;
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
            if (isset($this->cache[$cacheKey])) {
                return $this->cache[$cacheKey];
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
        $this->cache[$cacheKey] = $entries;

        return $entries;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $ledgerEntryId, bool $skipCache = false): PaymentAccountLedgerEntryInterface
    {
        if (!$skipCache) {
            if (isset($this->entryCache[$ledgerEntryId])) {
                return $this->entryCache[$ledgerEntryId];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $ledgerEntryId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $entry = $this->paymentAccountLedgerEntryTransformer->transform($data);
        $this->entryCache[$ledgerEntryId] = $entry;

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
