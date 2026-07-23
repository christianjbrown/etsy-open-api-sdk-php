<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntryInterface;

interface PaymentAccountLedgerEntriesTransformerInterface
{
    public const string ARRAY_NAME = 'payment_account_ledger_entry';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, PaymentAccountLedgerEntryInterface>
     */
    public function transform(array $data): array;
}
