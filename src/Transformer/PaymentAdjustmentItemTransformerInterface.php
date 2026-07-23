<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PaymentAdjustmentItemInterface;

interface PaymentAdjustmentItemTransformerInterface
{
    public const string KEY_ADJUSTMENT_TYPE = 'adjustment_type';
    public const string KEY_AMOUNT = 'amount';
    public const string KEY_BILL_PAYMENT_ID = 'bill_payment_id';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_PAYMENT_ADJUSTMENT_ID = 'payment_adjustment_id';
    public const string KEY_PAYMENT_ADJUSTMENT_ITEM_ID = 'payment_adjustment_item_id';
    public const string KEY_SHOP_AMOUNT = 'shop_amount';
    public const string KEY_TRANSACTION_ID = 'transaction_id';
    public const string KEY_UPDATED_TIMESTAMP = 'updated_timestamp';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PaymentAdjustmentItemInterface;
}
