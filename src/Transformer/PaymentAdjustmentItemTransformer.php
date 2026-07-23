<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PaymentAdjustmentItem;
use ChristianBrown\Etsy\Model\PaymentAdjustmentItemInterface;

use function is_int;
use function is_string;

final class PaymentAdjustmentItemTransformer implements PaymentAdjustmentItemTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PaymentAdjustmentItemInterface
    {
        $item = new PaymentAdjustmentItem();

        self::applyAdjustmentType($item, $data);
        self::applyAmount($item, $data);
        self::applyBillPaymentId($item, $data);
        self::applyCreatedTimestamp($item, $data);
        self::applyPaymentAdjustmentId($item, $data);
        self::applyPaymentAdjustmentItemId($item, $data);
        self::applyShopAmount($item, $data);
        self::applyTransactionId($item, $data);
        self::applyUpdatedTimestamp($item, $data);

        return $item;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyAdjustmentType(PaymentAdjustmentItem $item, array $data): void
    {
        if (empty($data[self::KEY_ADJUSTMENT_TYPE])) {
            return;
        }
        if (!is_string($data[self::KEY_ADJUSTMENT_TYPE])) {
            return;
        }
        $item->setAdjustmentType($data[self::KEY_ADJUSTMENT_TYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyAmount(PaymentAdjustmentItem $item, array $data): void
    {
        if (!isset($data[self::KEY_AMOUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_AMOUNT])) {
            return;
        }
        $item->setAmount($data[self::KEY_AMOUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBillPaymentId(PaymentAdjustmentItem $item, array $data): void
    {
        if (!isset($data[self::KEY_BILL_PAYMENT_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_BILL_PAYMENT_ID])) {
            return;
        }
        $item->setBillPaymentId($data[self::KEY_BILL_PAYMENT_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(PaymentAdjustmentItem $item, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $item->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPaymentAdjustmentId(PaymentAdjustmentItem $item, array $data): void
    {
        if (!isset($data[self::KEY_PAYMENT_ADJUSTMENT_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_PAYMENT_ADJUSTMENT_ID])) {
            return;
        }
        $item->setPaymentAdjustmentId($data[self::KEY_PAYMENT_ADJUSTMENT_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPaymentAdjustmentItemId(PaymentAdjustmentItem $item, array $data): void
    {
        if (!isset($data[self::KEY_PAYMENT_ADJUSTMENT_ITEM_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_PAYMENT_ADJUSTMENT_ITEM_ID])) {
            return;
        }
        $item->setPaymentAdjustmentItemId($data[self::KEY_PAYMENT_ADJUSTMENT_ITEM_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopAmount(PaymentAdjustmentItem $item, array $data): void
    {
        if (!isset($data[self::KEY_SHOP_AMOUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_SHOP_AMOUNT])) {
            return;
        }
        $item->setShopAmount($data[self::KEY_SHOP_AMOUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTransactionId(PaymentAdjustmentItem $item, array $data): void
    {
        if (!isset($data[self::KEY_TRANSACTION_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_TRANSACTION_ID])) {
            return;
        }
        $item->setTransactionId($data[self::KEY_TRANSACTION_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdatedTimestamp(PaymentAdjustmentItem $item, array $data): void
    {
        if (!isset($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        $item->setUpdatedTimestamp($data[self::KEY_UPDATED_TIMESTAMP]);
    }
}
