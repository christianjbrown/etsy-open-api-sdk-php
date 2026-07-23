<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopInterface;

interface ShopTransformerInterface
{
    public const string KEY_ACCEPTS_CUSTOM_REQUESTS = 'accepts_custom_requests';
    public const string KEY_ANNOUNCEMENT = 'announcement';
    public const string KEY_CREATE_DATE = 'create_date';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_CURRENCY_CODE = 'currency_code';
    public const string KEY_DIGITAL_LISTING_COUNT = 'digital_listing_count';
    public const string KEY_DIGITAL_SALE_MESSAGE = 'digital_sale_message';
    public const string KEY_HAS_ONBOARDED_STRUCTURED_POLICIES = 'has_onboarded_structured_policies';
    public const string KEY_HAS_UNSTRUCTURED_POLICIES = 'has_unstructured_policies';
    public const string KEY_ICON_URL_FULLXFULL = 'icon_url_fullxfull';
    public const string KEY_IMAGE_URL_760X100 = 'image_url_760x100';
    public const string KEY_INCLUDE_DISPUTE_FORM_LINK = 'include_dispute_form_link';
    public const string KEY_IS_CALCULATED_ELIGIBLE = 'is_calculated_eligible';
    public const string KEY_IS_DIRECT_CHECKOUT_ONBOARDED = 'is_direct_checkout_onboarded';
    public const string KEY_IS_ETSY_PAYMENTS_ONBOARDED = 'is_etsy_payments_onboarded';
    public const string KEY_IS_OPTED_IN_TO_BUYER_PROMISE = 'is_opted_in_to_buyer_promise';
    public const string KEY_IS_SHOP_US_BASED = 'is_shop_us_based';
    public const string KEY_IS_USING_STRUCTURED_POLICIES = 'is_using_structured_policies';
    public const string KEY_IS_VACATION = 'is_vacation';
    public const string KEY_LANGUAGES = 'languages';
    public const string KEY_LISTING_ACTIVE_COUNT = 'listing_active_count';
    public const string KEY_LOGIN_NAME = 'login_name';
    public const string KEY_NUM_FAVORERS = 'num_favorers';
    public const string KEY_POLICY_ADDITIONAL = 'policy_additional';
    public const string KEY_POLICY_HAS_PRIVATE_RECEIPT_INFO = 'policy_has_private_receipt_info';
    public const string KEY_POLICY_PAYMENT = 'policy_payment';
    public const string KEY_POLICY_PRIVACY = 'policy_privacy';
    public const string KEY_POLICY_REFUNDS = 'policy_refunds';
    public const string KEY_POLICY_SELLER_INFO = 'policy_seller_info';
    public const string KEY_POLICY_SHIPPING = 'policy_shipping';
    public const string KEY_POLICY_UPDATE_DATE = 'policy_update_date';
    public const string KEY_POLICY_WELCOME = 'policy_welcome';
    public const string KEY_REVIEW_AVERAGE = 'review_average';
    public const string KEY_REVIEW_COUNT = 'review_count';
    public const string KEY_SALE_MESSAGE = 'sale_message';
    public const string KEY_SHIPPING_FROM_COUNTRY_ISO = 'shipping_from_country_iso';
    public const string KEY_SHOP_ID = 'shop_id';
    public const string KEY_SHOP_LOCATION_COUNTRY_ISO = 'shop_location_country_iso';
    public const string KEY_SHOP_NAME = 'shop_name';
    public const string KEY_TITLE = 'title';
    public const string KEY_TRANSACTION_SOLD_COUNT = 'transaction_sold_count';
    public const string KEY_UPDATE_DATE = 'update_date';
    public const string KEY_UPDATED_TIMESTAMP = 'updated_timestamp';
    public const string KEY_URL = 'url';
    public const string KEY_USER_ID = 'user_id';
    public const string KEY_VACATION_AUTOREPLY = 'vacation_autoreply';
    public const string KEY_VACATION_MESSAGE = 'vacation_message';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopInterface;
}
