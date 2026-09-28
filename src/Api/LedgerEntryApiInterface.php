<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntryInterface;

interface LedgerEntryApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/payment-account/ledger-entries';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/payment-account/ledger-entries/%d';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_MAX_CREATED = 'max_created';
    public const string KEY_MIN_CREATED = 'min_created';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Reads a single page of the shop's payment account ledger entries.
     *
     * @return array<int, PaymentAccountLedgerEntryInterface>
     */
    public function getMultiple(?int $minCreated = null, ?int $maxCreated = null, int $limit = 25, int $offset = 0, bool $skipCache = false): array;

    public function getOneById(int $ledgerEntryId, bool $skipCache = false): PaymentAccountLedgerEntryInterface;
}
