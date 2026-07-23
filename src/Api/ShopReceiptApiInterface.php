<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ReceiptInterface;

interface ShopReceiptApiInterface extends ApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/receipts';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/receipts/%d';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Reads a single page of the shop's receipts, most recent first.
     *
     * @return array<int, ReceiptInterface>
     */
    public function getMultiple(int $limit = 100, int $offset = 0, bool $skipCache = false): array;

    public function getOneById(int $receiptId, bool $skipCache = false): ReceiptInterface;
}
