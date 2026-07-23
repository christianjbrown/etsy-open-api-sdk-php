<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\TransactionInterface;

interface TransactionTransformerInterface
{
    public const string KEY_BUYER_COUPON = 'buyer_coupon';
    public const string KEY_BUYER_USER_ID = 'buyer_user_id';
    public const string KEY_CREATE_TIMESTAMP = 'create_timestamp';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_DESCRIPTION = 'description';
    public const string KEY_EXPECTED_SHIP_DATE = 'expected_ship_date';
    public const string KEY_FILE_DATA = 'file_data';
    public const string KEY_IS_DIGITAL = 'is_digital';
    public const string KEY_LISTING_ID = 'listing_id';
    public const string KEY_LISTING_IMAGE_ID = 'listing_image_id';
    public const string KEY_MAX_PROCESSING_DAYS = 'max_processing_days';
    public const string KEY_MIN_PROCESSING_DAYS = 'min_processing_days';
    public const string KEY_PAID_TIMESTAMP = 'paid_timestamp';
    public const string KEY_PRICE = 'price';
    public const string KEY_PRODUCT_DATA = 'product_data';
    public const string KEY_PRODUCT_ID = 'product_id';
    public const string KEY_QUANTITY = 'quantity';
    public const string KEY_RECEIPT_ID = 'receipt_id';
    public const string KEY_SELLER_USER_ID = 'seller_user_id';
    public const string KEY_SHIPPED_TIMESTAMP = 'shipped_timestamp';
    public const string KEY_SHIPPING_COST = 'shipping_cost';
    public const string KEY_SHIPPING_METHOD = 'shipping_method';
    public const string KEY_SHIPPING_PROFILE_ID = 'shipping_profile_id';
    public const string KEY_SHIPPING_UPGRADE = 'shipping_upgrade';
    public const string KEY_SHOP_COUPON = 'shop_coupon';
    public const string KEY_SKU = 'sku';
    public const string KEY_TITLE = 'title';
    public const string KEY_TRANSACTION_ID = 'transaction_id';
    public const string KEY_TRANSACTION_TYPE = 'transaction_type';
    public const string KEY_VARIATIONS = 'variations';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): TransactionInterface;
}
