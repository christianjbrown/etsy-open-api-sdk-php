<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\CreateReceiptShipmentRequestInterface;
use ChristianBrown\Etsy\Model\ReceiptInterface;
use ChristianBrown\Etsy\Model\ReceiptPageInterface;
use ChristianBrown\Etsy\Model\UpdateShopReceiptRequestInterface;

interface ShopReceiptApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/receipts';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/receipts/%d';
    public const string API_URL_TRACKING_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/receipts/%d/tracking';
    public const string KEY_LEGACY = 'legacy';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Submits tracking information for a receipt's shipment, notifying the buyer.
     */
    public function createReceiptShipment(int $receiptId, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest, bool $legacy = false): ReceiptInterface;

    /**
     * Reads a single page of the shop's receipts, most recent first.
     *
     * @return array<int, ReceiptInterface>
     */
    public function getMultiple(int $limit = 100, int $offset = 0, bool $skipCache = false): array;

    public function getOneById(int $receiptId, bool $skipCache = false): ReceiptInterface;

    /**
     * Reads a single page of the shop's receipts together with the shop's total
     * receipt count, so a caller can page through the whole set without having
     * to infer the end from a short page.
     */
    public function getPage(int $limit = 100, int $offset = 0, bool $skipCache = false): ReceiptPageInterface;

    /**
     * Updates the shipped/paid status of a receipt.
     */
    public function updateShopReceipt(int $receiptId, UpdateShopReceiptRequestInterface $updateShopReceiptRequest, bool $legacy = false): ReceiptInterface;
}
