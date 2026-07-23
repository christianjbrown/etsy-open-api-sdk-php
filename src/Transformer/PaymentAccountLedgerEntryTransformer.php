<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntry;
use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntryInterface;

use function is_array;
use function is_int;
use function is_string;
use function sprintf;

final class PaymentAccountLedgerEntryTransformer implements PaymentAccountLedgerEntryTransformerInterface
{
    private PaymentAdjustmentsTransformerInterface $paymentAdjustmentsTransformer;

    public function __construct(PaymentAdjustmentsTransformerInterface $paymentAdjustmentsTransformer)
    {
        $this->paymentAdjustmentsTransformer = $paymentAdjustmentsTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PaymentAccountLedgerEntryInterface
    {
        if (!isset($data[self::KEY_ENTRY_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_ENTRY_ID));
        }
        if (!is_int($data[self::KEY_ENTRY_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_ENTRY_ID));
        }
        $entry = new PaymentAccountLedgerEntry($data[self::KEY_ENTRY_ID]);

        self::applyAmount($entry, $data);
        self::applyBalance($entry, $data);
        self::applyCreateDate($entry, $data);
        self::applyCreatedTimestamp($entry, $data);
        self::applyCurrency($entry, $data);
        self::applyDescription($entry, $data);
        self::applyLedgerId($entry, $data);
        self::applyLedgerType($entry, $data);
        self::applyParentEntryId($entry, $data);
        self::applyReferenceId($entry, $data);
        self::applyReferenceType($entry, $data);
        self::applySequenceNumber($entry, $data);
        $this->applyPaymentAdjustments($entry, $data);

        return $entry;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyAmount(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (!isset($data[self::KEY_AMOUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_AMOUNT])) {
            return;
        }
        $entry->setAmount($data[self::KEY_AMOUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBalance(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (!isset($data[self::KEY_BALANCE])) {
            return;
        }
        if (!is_int($data[self::KEY_BALANCE])) {
            return;
        }
        $entry->setBalance($data[self::KEY_BALANCE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreateDate(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (!isset($data[self::KEY_CREATE_DATE])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATE_DATE])) {
            return;
        }
        $entry->setCreateDate($data[self::KEY_CREATE_DATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $entry->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCurrency(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (empty($data[self::KEY_CURRENCY])) {
            return;
        }
        if (!is_string($data[self::KEY_CURRENCY])) {
            return;
        }
        $entry->setCurrency($data[self::KEY_CURRENCY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDescription(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (empty($data[self::KEY_DESCRIPTION])) {
            return;
        }
        if (!is_string($data[self::KEY_DESCRIPTION])) {
            return;
        }
        $entry->setDescription($data[self::KEY_DESCRIPTION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLedgerId(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (!isset($data[self::KEY_LEDGER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_LEDGER_ID])) {
            return;
        }
        $entry->setLedgerId($data[self::KEY_LEDGER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLedgerType(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (empty($data[self::KEY_LEDGER_TYPE])) {
            return;
        }
        if (!is_string($data[self::KEY_LEDGER_TYPE])) {
            return;
        }
        $entry->setLedgerType($data[self::KEY_LEDGER_TYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyParentEntryId(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (!isset($data[self::KEY_PARENT_ENTRY_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_PARENT_ENTRY_ID])) {
            return;
        }
        $entry->setParentEntryId($data[self::KEY_PARENT_ENTRY_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPaymentAdjustments(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (empty($data[self::KEY_PAYMENT_ADJUSTMENTS])) {
            return;
        }
        if (!is_array($data[self::KEY_PAYMENT_ADJUSTMENTS])) {
            return;
        }
        $entry->setPaymentAdjustments($this->paymentAdjustmentsTransformer->transform($data[self::KEY_PAYMENT_ADJUSTMENTS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReferenceId(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (empty($data[self::KEY_REFERENCE_ID])) {
            return;
        }
        if (!is_string($data[self::KEY_REFERENCE_ID])) {
            return;
        }
        $entry->setReferenceId($data[self::KEY_REFERENCE_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReferenceType(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (empty($data[self::KEY_REFERENCE_TYPE])) {
            return;
        }
        if (!is_string($data[self::KEY_REFERENCE_TYPE])) {
            return;
        }
        $entry->setReferenceType($data[self::KEY_REFERENCE_TYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySequenceNumber(PaymentAccountLedgerEntry $entry, array $data): void
    {
        if (!isset($data[self::KEY_SEQUENCE_NUMBER])) {
            return;
        }
        if (!is_int($data[self::KEY_SEQUENCE_NUMBER])) {
            return;
        }
        $entry->setSequenceNumber($data[self::KEY_SEQUENCE_NUMBER]);
    }
}
