<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntryInterface;

interface PaymentAccountLedgerEntryTransformerInterface
{
    public const string KEY_AMOUNT = 'amount';
    public const string KEY_BALANCE = 'balance';
    public const string KEY_CREATE_DATE = 'create_date';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_CURRENCY = 'currency';
    public const string KEY_DESCRIPTION = 'description';
    public const string KEY_ENTRY_ID = 'entry_id';
    public const string KEY_LEDGER_ID = 'ledger_id';
    public const string KEY_LEDGER_TYPE = 'ledger_type';
    public const string KEY_PARENT_ENTRY_ID = 'parent_entry_id';
    public const string KEY_PAYMENT_ADJUSTMENTS = 'payment_adjustments';
    public const string KEY_REFERENCE_ID = 'reference_id';
    public const string KEY_REFERENCE_TYPE = 'reference_type';
    public const string KEY_SEQUENCE_NUMBER = 'sequence_number';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PaymentAccountLedgerEntryInterface;
}
