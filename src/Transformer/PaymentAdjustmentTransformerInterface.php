<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PaymentAdjustmentInterface;

interface PaymentAdjustmentTransformerInterface
{
    public const string KEY_BUYER_TOTAL_ADJUSTMENT_AMOUNT = 'buyer_total_adjustment_amount';
    public const string KEY_CREATE_TIMESTAMP = 'create_timestamp';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_IS_SUCCESS = 'is_success';
    public const string KEY_PAYMENT_ADJUSTMENT_ID = 'payment_adjustment_id';
    public const string KEY_PAYMENT_ADJUSTMENT_ITEMS = 'payment_adjustment_items';
    public const string KEY_PAYMENT_ID = 'payment_id';
    public const string KEY_REASON_CODE = 'reason_code';
    public const string KEY_SHOP_TOTAL_ADJUSTMENT_AMOUNT = 'shop_total_adjustment_amount';
    public const string KEY_STATUS = 'status';
    public const string KEY_TOTAL_ADJUSTMENT_AMOUNT = 'total_adjustment_amount';
    public const string KEY_TOTAL_FEE_ADJUSTMENT_AMOUNT = 'total_fee_adjustment_amount';
    public const string KEY_UPDATE_TIMESTAMP = 'update_timestamp';
    public const string KEY_UPDATED_TIMESTAMP = 'updated_timestamp';
    public const string KEY_USER_ID = 'user_id';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PaymentAdjustmentInterface;
}
