<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ReceiptInterface;

interface ReceiptTransformerInterface
{
    public const string KEY_BUYER_EMAIL = 'buyer_email';
    public const string KEY_BUYER_USER_ID = 'buyer_user_id';
    public const string KEY_CITY = 'city';
    public const string KEY_COUNTRY_ISO = 'country_iso';
    public const string KEY_CREATE_TIMESTAMP = 'create_timestamp';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_DISCOUNT_AMT = 'discount_amt';
    public const string KEY_FIRST_LINE = 'first_line';
    public const string KEY_FORMATTED_ADDRESS = 'formatted_address';
    public const string KEY_GIFT_MESSAGE = 'gift_message';
    public const string KEY_GIFT_SENDER = 'gift_sender';
    public const string KEY_GIFT_WRAP_PRICE = 'gift_wrap_price';
    public const string KEY_GRANDTOTAL = 'grandtotal';
    public const string KEY_IS_GIFT = 'is_gift';
    public const string KEY_IS_PAID = 'is_paid';
    public const string KEY_IS_SHIPPED = 'is_shipped';
    public const string KEY_MESSAGE_FROM_BUYER = 'message_from_buyer';
    public const string KEY_MESSAGE_FROM_PAYMENT = 'message_from_payment';
    public const string KEY_MESSAGE_FROM_SELLER = 'message_from_seller';
    public const string KEY_NAME = 'name';
    public const string KEY_PAYMENT_EMAIL = 'payment_email';
    public const string KEY_PAYMENT_METHOD = 'payment_method';
    public const string KEY_RECEIPT_ID = 'receipt_id';
    public const string KEY_RECEIPT_TYPE = 'receipt_type';
    public const string KEY_REFUNDS = 'refunds';
    public const string KEY_SECOND_LINE = 'second_line';
    public const string KEY_SELLER_EMAIL = 'seller_email';
    public const string KEY_SELLER_USER_ID = 'seller_user_id';
    public const string KEY_SHIPMENTS = 'shipments';
    public const string KEY_STATE = 'state';
    public const string KEY_STATUS = 'status';
    public const string KEY_SUBTOTAL = 'subtotal';
    public const string KEY_TOTAL_PRICE = 'total_price';
    public const string KEY_TOTAL_SHIPPING_COST = 'total_shipping_cost';
    public const string KEY_TOTAL_TAX_COST = 'total_tax_cost';
    public const string KEY_TOTAL_VAT_COST = 'total_vat_cost';
    public const string KEY_TRANSACTIONS = 'transactions';
    public const string KEY_UPDATE_TIMESTAMP = 'update_timestamp';
    public const string KEY_UPDATED_TIMESTAMP = 'updated_timestamp';
    public const string KEY_ZIP = 'zip';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ReceiptInterface;
}
