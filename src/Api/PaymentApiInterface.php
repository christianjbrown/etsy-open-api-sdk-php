<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\PaymentInterface;

interface PaymentApiInterface
{
    public const string API_URL_BY_LEDGER_ENTRY_IDS_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/payment-account/ledger-entries/payments';
    public const string API_URL_BY_PAYMENT_IDS_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/payments';
    public const string API_URL_BY_RECEIPT_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/receipts/%d/payments';
    public const string KEY_LEDGER_ENTRY_IDS = 'ledger_entry_ids';
    public const string KEY_PAYMENT_IDS = 'payment_ids';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * @param array<int, int> $ledgerEntryIds
     *
     * @return array<int, PaymentInterface>
     */
    public function getByLedgerEntryIds(array $ledgerEntryIds, bool $skipCache = false): array;

    /**
     * @param array<int, int> $paymentIds
     *
     * @return array<int, PaymentInterface>
     */
    public function getByPaymentIds(array $paymentIds, bool $skipCache = false): array;

    /**
     * @return array<int, PaymentInterface>
     */
    public function getByReceipt(int $receiptId, bool $skipCache = false): array;
}
