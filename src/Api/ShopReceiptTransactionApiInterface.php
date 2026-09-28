<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\TransactionInterface;

interface ShopReceiptTransactionApiInterface
{
    public const string API_URL_BY_LISTING_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/transactions';
    public const string API_URL_BY_RECEIPT_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/receipts/%d/transactions';
    public const string API_URL_BY_SHOP_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/transactions';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/transactions/%d';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * @return array<int, TransactionInterface>
     */
    public function getByListing(int $listingId, int $limit = 25, int $offset = 0, bool $skipCache = false): array;

    /**
     * @return array<int, TransactionInterface>
     */
    public function getByReceipt(int $receiptId, bool $skipCache = false): array;

    /**
     * @return array<int, TransactionInterface>
     */
    public function getByShop(int $limit = 25, int $offset = 0, bool $skipCache = false): array;

    public function getOneById(int $transactionId, bool $skipCache = false): TransactionInterface;
}
