<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PaymentInterface;

interface PaymentTransformerInterface
{
    public const string KEY_ADJUSTED_FEES = 'adjusted_fees';
    public const string KEY_ADJUSTED_GROSS = 'adjusted_gross';
    public const string KEY_ADJUSTED_NET = 'adjusted_net';
    public const string KEY_AMOUNT_FEES = 'amount_fees';
    public const string KEY_AMOUNT_GROSS = 'amount_gross';
    public const string KEY_AMOUNT_NET = 'amount_net';
    public const string KEY_BILLING_ADDRESS_ID = 'billing_address_id';
    public const string KEY_BUYER_CURRENCY = 'buyer_currency';
    public const string KEY_BUYER_USER_ID = 'buyer_user_id';
    public const string KEY_CREATE_TIMESTAMP = 'create_timestamp';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_CURRENCY = 'currency';
    public const string KEY_PAYMENT_ADJUSTMENTS = 'payment_adjustments';
    public const string KEY_PAYMENT_ID = 'payment_id';
    public const string KEY_POSTED_FEES = 'posted_fees';
    public const string KEY_POSTED_GROSS = 'posted_gross';
    public const string KEY_POSTED_NET = 'posted_net';
    public const string KEY_RECEIPT_ID = 'receipt_id';
    public const string KEY_SHIPPED_TIMESTAMP = 'shipped_timestamp';
    public const string KEY_SHIPPING_ADDRESS_ID = 'shipping_address_id';
    public const string KEY_SHIPPING_USER_ID = 'shipping_user_id';
    public const string KEY_SHOP_CURRENCY = 'shop_currency';
    public const string KEY_SHOP_ID = 'shop_id';
    public const string KEY_STATUS = 'status';
    public const string KEY_UPDATE_TIMESTAMP = 'update_timestamp';
    public const string KEY_UPDATED_TIMESTAMP = 'updated_timestamp';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PaymentInterface;
}
