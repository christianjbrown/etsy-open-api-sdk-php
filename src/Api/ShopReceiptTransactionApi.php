<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TransactionInterface;
use ChristianBrown\Etsy\Transformer\TransactionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\TransactionTransformerInterface;

use function is_array;
use function sprintf;

final class ShopReceiptTransactionApi implements ShopReceiptTransactionApiInterface
{
    /**
     * @var array<int, TransactionInterface>
     */
    private array $byIdCache = [];

    /**
     * @var array<string, array<int, TransactionInterface>>
     */
    private array $byListingCache = [];

    /**
     * @var array<int, array<int, TransactionInterface>>
     */
    private array $byReceiptCache = [];

    /**
     * @var array<string, array<int, TransactionInterface>>
     */
    private array $byShopCache = [];
    private CredentialsInterface $credentials;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private TransactionsTransformerInterface $transactionsTransformer;
    private TransactionTransformerInterface $transactionTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, TransactionTransformerInterface $transactionTransformer, TransactionsTransformerInterface $transactionsTransformer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->transactionTransformer = $transactionTransformer;
        $this->transactionsTransformer = $transactionsTransformer;
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
            if (isset($this->byListingCache[$cacheKey])) {
                return $this->byListingCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_BY_LISTING_SPRINTF, $this->shopId, $listingId);
        $data = $this->requestSender->get($url, self::buildQuery($limit, $offset), $this->credentials->toHeaders());

        $transactions = $this->handleResults($data);
        $this->byListingCache[$cacheKey] = $transactions;

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
            if (isset($this->byReceiptCache[$receiptId])) {
                return $this->byReceiptCache[$receiptId];
            }
        }

        $url = sprintf(self::API_URL_BY_RECEIPT_SPRINTF, $this->shopId, $receiptId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        $transactions = $this->handleResults($data);
        $this->byReceiptCache[$receiptId] = $transactions;

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
            if (isset($this->byShopCache[$cacheKey])) {
                return $this->byShopCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_BY_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildQuery($limit, $offset), $this->credentials->toHeaders());

        $transactions = $this->handleResults($data);
        $this->byShopCache[$cacheKey] = $transactions;

        return $transactions;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $transactionId, bool $skipCache = false): TransactionInterface
    {
        if (!$skipCache) {
            if (isset($this->byIdCache[$transactionId])) {
                return $this->byIdCache[$transactionId];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $transactionId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $transaction = $this->transactionTransformer->transform($data);
        $this->byIdCache[$transactionId] = $transaction;

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
