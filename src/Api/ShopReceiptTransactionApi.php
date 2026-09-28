<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TransactionInterface;
use ChristianBrown\Etsy\Transformer\TransactionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\TransactionTransformerInterface;

use function is_array;
use function sprintf;

final class ShopReceiptTransactionApi implements ShopReceiptTransactionApiInterface
{
    private ResponseCacheInterface $byIdCache;
    private ResponseCacheInterface $byListingCache;
    private ResponseCacheInterface $byReceiptCache;
    private ResponseCacheInterface $byShopCache;
    private CredentialsInterface $credentials;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private TransactionsTransformerInterface $transactionsTransformer;
    private TransactionTransformerInterface $transactionTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, TransactionTransformerInterface $transactionTransformer, TransactionsTransformerInterface $transactionsTransformer, ResponseCacheInterface $byIdCache, ResponseCacheInterface $byListingCache, ResponseCacheInterface $byReceiptCache, ResponseCacheInterface $byShopCache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->transactionTransformer = $transactionTransformer;
        $this->transactionsTransformer = $transactionsTransformer;
        $this->byIdCache = $byIdCache;
        $this->byListingCache = $byListingCache;
        $this->byReceiptCache = $byReceiptCache;
        $this->byShopCache = $byShopCache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, TransactionInterface>
     */
    public function getByListing(int $listingId, int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%d:%d:%d', $listingId, $limit, $offset);
        if (!$skipCache) {
            if ($this->byListingCache->has($cacheKey)) {
                /**
                 * @var array<int, TransactionInterface> $cached
                 */
                $cached = $this->byListingCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_LISTING_SPRINTF, $this->shopId, $listingId);
        $data = $this->requestSender->get($url, self::buildQuery($limit, $offset), $this->credentials->toHeaders());

        $transactions = $this->handleResults($data);
        $this->byListingCache->set($cacheKey, $transactions);

        return $transactions;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, TransactionInterface>
     */
    public function getByReceipt(int $receiptId, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if ($this->byReceiptCache->has((string) $receiptId)) {
                /**
                 * @var array<int, TransactionInterface> $cached
                 */
                $cached = $this->byReceiptCache->get((string) $receiptId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_RECEIPT_SPRINTF, $this->shopId, $receiptId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        $transactions = $this->handleResults($data);
        $this->byReceiptCache->set((string) $receiptId, $transactions);

        return $transactions;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, TransactionInterface>
     */
    public function getByShop(int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%d:%d', $limit, $offset);
        if (!$skipCache) {
            if ($this->byShopCache->has($cacheKey)) {
                /**
                 * @var array<int, TransactionInterface> $cached
                 */
                $cached = $this->byShopCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildQuery($limit, $offset), $this->credentials->toHeaders());

        $transactions = $this->handleResults($data);
        $this->byShopCache->set($cacheKey, $transactions);

        return $transactions;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $transactionId, bool $skipCache = false): TransactionInterface
    {
        if (!$skipCache) {
            if ($this->byIdCache->has((string) $transactionId)) {
                /**
                 * @var TransactionInterface $cached
                 */
                $cached = $this->byIdCache->get((string) $transactionId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $transactionId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $transaction = $this->transactionTransformer->transform($data);
        $this->byIdCache->set((string) $transactionId, $transaction);

        return $transaction;
    }

    /**
     * @return array<string, string>
     */
    private static function buildQuery(int $limit, int $offset): array
    {
        return [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
    }

    /**
     * @param mixed[] $data
     *
     * @throws UnexpectedResponseException
     *
     * @return array<int, TransactionInterface>
     */
    private function handleResults(array $data): array
    {
        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }

        return $this->transactionsTransformer->transform($data[self::KEY_RESULTS]);
    }
}
