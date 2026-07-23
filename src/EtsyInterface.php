<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy;

use ChristianBrown\Etsy\Api\LedgerEntryApiInterface;
use ChristianBrown\Etsy\Api\ListingFileApiInterface;
use ChristianBrown\Etsy\Api\ListingImageApiInterface;
use ChristianBrown\Etsy\Api\ListingInventoryApiInterface;
use ChristianBrown\Etsy\Api\ListingPersonalizationApiInterface;
use ChristianBrown\Etsy\Api\ListingPropertyApiInterface;
use ChristianBrown\Etsy\Api\ListingTranslationApiInterface;
use ChristianBrown\Etsy\Api\ListingVariationImageApiInterface;
use ChristianBrown\Etsy\Api\ListingVideoApiInterface;
use ChristianBrown\Etsy\Api\PaymentApiInterface;
use ChristianBrown\Etsy\Api\PingApiInterface;
use ChristianBrown\Etsy\Api\ShopApiInterface;
use ChristianBrown\Etsy\Api\ShopHolidayPreferenceApiInterface;
use ChristianBrown\Etsy\Api\ShopListingApiInterface;
use ChristianBrown\Etsy\Api\ShopProductionPartnerApiInterface;
use ChristianBrown\Etsy\Api\ShopReadinessStateDefinitionApiInterface;
use ChristianBrown\Etsy\Api\ShopReceiptApiInterface;
use ChristianBrown\Etsy\Api\ShopReceiptTransactionApiInterface;
use ChristianBrown\Etsy\Api\ShopReturnPolicyApiInterface;
use ChristianBrown\Etsy\Api\ShopSectionApiInterface;
use ChristianBrown\Etsy\Api\UserAddressApiInterface;
use ChristianBrown\Etsy\Api\UserApiInterface;

interface EtsyInterface
{
    public const string OAUTH_TOKEN_URL = 'https://api.etsy.com/v3/public/oauth/token';
    public const string SERVICE_ACCESS_TOKEN_TRANSFORMER = 'etsy.oauth.access_token_transformer';
    public const string SERVICE_API_CLIENT = 'etsy.api_client';
    public const string SERVICE_CREDENTIALS = 'etsy.auth.credentials';
    public const string SERVICE_JSON_API_REQUEST_SENDER = 'etsy.json_api_request_sender';
    public const string SERVICE_LEDGER_ENTRY_API = 'etsy.api.ledger_entry_api';
    public const string SERVICE_LISTING_FILE_API = 'etsy.api.listing_file_api';
    public const string SERVICE_LISTING_FILE_TRANSFORMER = 'etsy.transformer.listing_file_transformer';
    public const string SERVICE_LISTING_FILES_TRANSFORMER = 'etsy.transformer.listing_files_transformer';
    public const string SERVICE_LISTING_IMAGE_API = 'etsy.api.listing_image_api';
    public const string SERVICE_LISTING_IMAGE_TRANSFORMER = 'etsy.transformer.listing_image_transformer';
    public const string SERVICE_LISTING_IMAGES_TRANSFORMER = 'etsy.transformer.listing_images_transformer';
    public const string SERVICE_LISTING_INVENTORY_API = 'etsy.api.listing_inventory_api';
    public const string SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_TRANSFORMER = 'etsy.transformer.listing_inventory_product_offering_transformer';
    public const string SERVICE_LISTING_INVENTORY_PRODUCT_OFFERINGS_TRANSFORMER = 'etsy.transformer.listing_inventory_product_offerings_transformer';
    public const string SERVICE_LISTING_INVENTORY_PRODUCT_TRANSFORMER = 'etsy.transformer.listing_inventory_product_transformer';
    public const string SERVICE_LISTING_INVENTORY_PRODUCTS_TRANSFORMER = 'etsy.transformer.listing_inventory_products_transformer';
    public const string SERVICE_LISTING_INVENTORY_TRANSFORMER = 'etsy.transformer.listing_inventory_transformer';
    public const string SERVICE_LISTING_PERSONALIZATION_API = 'etsy.api.listing_personalization_api';
    public const string SERVICE_LISTING_PERSONALIZATION_TRANSFORMER = 'etsy.transformer.listing_personalization_transformer';
    public const string SERVICE_LISTING_PROPERTY_API = 'etsy.api.listing_property_api';
    public const string SERVICE_LISTING_PROPERTY_VALUE_TRANSFORMER = 'etsy.transformer.listing_property_value_transformer';
    public const string SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER = 'etsy.transformer.listing_property_values_transformer';
    public const string SERVICE_LISTING_TRANSFORMER = 'etsy.transformer.listing_transformer';
    public const string SERVICE_LISTING_TRANSLATION_API = 'etsy.api.listing_translation_api';
    public const string SERVICE_LISTING_TRANSLATION_TRANSFORMER = 'etsy.transformer.listing_translation_transformer';
    public const string SERVICE_LISTING_VARIATION_IMAGE_API = 'etsy.api.listing_variation_image_api';
    public const string SERVICE_LISTING_VARIATION_IMAGE_TRANSFORMER = 'etsy.transformer.listing_variation_image_transformer';
    public const string SERVICE_LISTING_VARIATION_IMAGES_TRANSFORMER = 'etsy.transformer.listing_variation_images_transformer';
    public const string SERVICE_LISTING_VIDEO_API = 'etsy.api.listing_video_api';
    public const string SERVICE_LISTING_VIDEO_TRANSFORMER = 'etsy.transformer.listing_video_transformer';
    public const string SERVICE_LISTING_VIDEOS_TRANSFORMER = 'etsy.transformer.listing_videos_transformer';
    public const string SERVICE_LISTINGS_TRANSFORMER = 'etsy.transformer.listings_transformer';
    public const string SERVICE_MONEY_TRANSFORMER = 'etsy.transformer.money_transformer';
    public const string SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRIES_TRANSFORMER = 'etsy.transformer.payment_account_ledger_entries_transformer';
    public const string SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRY_TRANSFORMER = 'etsy.transformer.payment_account_ledger_entry_transformer';
    public const string SERVICE_PAYMENT_ADJUSTMENT_ITEM_TRANSFORMER = 'etsy.transformer.payment_adjustment_item_transformer';
    public const string SERVICE_PAYMENT_ADJUSTMENT_ITEMS_TRANSFORMER = 'etsy.transformer.payment_adjustment_items_transformer';
    public const string SERVICE_PAYMENT_ADJUSTMENT_TRANSFORMER = 'etsy.transformer.payment_adjustment_transformer';
    public const string SERVICE_PAYMENT_ADJUSTMENTS_TRANSFORMER = 'etsy.transformer.payment_adjustments_transformer';
    public const string SERVICE_PAYMENT_API = 'etsy.api.payment_api';
    public const string SERVICE_PAYMENT_TRANSFORMER = 'etsy.transformer.payment_transformer';
    public const string SERVICE_PAYMENTS_TRANSFORMER = 'etsy.transformer.payments_transformer';
    public const string SERVICE_PERSONALIZATION_QUESTION_OPTION_TRANSFORMER = 'etsy.transformer.personalization_question_option_transformer';
    public const string SERVICE_PERSONALIZATION_QUESTION_OPTIONS_TRANSFORMER = 'etsy.transformer.personalization_question_options_transformer';
    public const string SERVICE_PERSONALIZATION_QUESTION_TRANSFORMER = 'etsy.transformer.personalization_question_transformer';
    public const string SERVICE_PERSONALIZATION_QUESTIONS_TRANSFORMER = 'etsy.transformer.personalization_questions_transformer';
    public const string SERVICE_PING_API = 'etsy.api.ping_api';
    public const string SERVICE_PING_TRANSFORMER = 'etsy.transformer.ping_transformer';
    public const string SERVICE_RECEIPT_TRANSFORMER = 'etsy.transformer.receipt_transformer';
    public const string SERVICE_RECEIPTS_TRANSFORMER = 'etsy.transformer.receipts_transformer';
    public const string SERVICE_REFRESH_TOKEN_MANAGER = 'etsy.oauth.refresh_token_manager';
    public const string SERVICE_REFUND_TRANSFORMER = 'etsy.transformer.refund_transformer';
    public const string SERVICE_REFUNDS_TRANSFORMER = 'etsy.transformer.refunds_transformer';
    public const string SERVICE_SHIPMENT_TRANSFORMER = 'etsy.transformer.shipment_transformer';
    public const string SERVICE_SHIPMENTS_TRANSFORMER = 'etsy.transformer.shipments_transformer';
    public const string SERVICE_SHOP_API = 'etsy.api.shop_api';
    public const string SERVICE_SHOP_HOLIDAY_PREFERENCE_API = 'etsy.api.shop_holiday_preference_api';
    public const string SERVICE_SHOP_HOLIDAY_PREFERENCE_TRANSFORMER = 'etsy.transformer.shop_holiday_preference_transformer';
    public const string SERVICE_SHOP_HOLIDAY_PREFERENCES_TRANSFORMER = 'etsy.transformer.shop_holiday_preferences_transformer';
    public const string SERVICE_SHOP_LISTING_API = 'etsy.api.shop_listing_api';
    public const string SERVICE_SHOP_PRODUCTION_PARTNER_API = 'etsy.api.shop_production_partner_api';
    public const string SERVICE_SHOP_PRODUCTION_PARTNER_TRANSFORMER = 'etsy.transformer.shop_production_partner_transformer';
    public const string SERVICE_SHOP_PRODUCTION_PARTNERS_TRANSFORMER = 'etsy.transformer.shop_production_partners_transformer';
    public const string SERVICE_SHOP_READINESS_STATE_DEFINITION_API = 'etsy.api.shop_readiness_state_definition_api';
    public const string SERVICE_SHOP_READINESS_STATE_DEFINITION_TRANSFORMER = 'etsy.transformer.shop_readiness_state_definition_transformer';
    public const string SERVICE_SHOP_READINESS_STATE_DEFINITIONS_TRANSFORMER = 'etsy.transformer.shop_readiness_state_definitions_transformer';
    public const string SERVICE_SHOP_RECEIPT_API = 'etsy.api.shop_receipt_api';
    public const string SERVICE_SHOP_RECEIPT_TRANSACTION_API = 'etsy.api.shop_receipt_transaction_api';
    public const string SERVICE_SHOP_RETURN_POLICIES_TRANSFORMER = 'etsy.transformer.shop_return_policies_transformer';
    public const string SERVICE_SHOP_RETURN_POLICY_API = 'etsy.api.shop_return_policy_api';
    public const string SERVICE_SHOP_RETURN_POLICY_TRANSFORMER = 'etsy.transformer.shop_return_policy_transformer';
    public const string SERVICE_SHOP_SECTION_API = 'etsy.api.shop_section_api';
    public const string SERVICE_SHOP_SECTION_TRANSFORMER = 'etsy.transformer.shop_section_transformer';
    public const string SERVICE_SHOP_SECTIONS_TRANSFORMER = 'etsy.transformer.shop_sections_transformer';
    public const string SERVICE_SHOP_TRANSFORMER = 'etsy.transformer.shop_transformer';
    public const string SERVICE_SHOPS_TRANSFORMER = 'etsy.transformer.shops_transformer';
    public const string SERVICE_TRANSACTION_TRANSFORMER = 'etsy.transformer.transaction_transformer';
    public const string SERVICE_TRANSACTION_VARIATION_TRANSFORMER = 'etsy.transformer.transaction_variation_transformer';
    public const string SERVICE_TRANSACTION_VARIATIONS_TRANSFORMER = 'etsy.transformer.transaction_variations_transformer';
    public const string SERVICE_TRANSACTIONS_TRANSFORMER = 'etsy.transformer.transactions_transformer';
    public const string SERVICE_USER_ADDRESS_API = 'etsy.api.user_address_api';
    public const string SERVICE_USER_ADDRESS_TRANSFORMER = 'etsy.transformer.user_address_transformer';
    public const string SERVICE_USER_ADDRESSES_TRANSFORMER = 'etsy.transformer.user_addresses_transformer';
    public const string SERVICE_USER_API = 'etsy.api.user_api';
    public const string SERVICE_USER_TRANSFORMER = 'etsy.transformer.user_transformer';

    public function getLedgerEntryApi(): LedgerEntryApiInterface;

    public function getListingFileApi(): ListingFileApiInterface;

    public function getListingImageApi(): ListingImageApiInterface;

    public function getListingInventoryApi(): ListingInventoryApiInterface;

    public function getListingPersonalizationApi(): ListingPersonalizationApiInterface;

    public function getListingPropertyApi(): ListingPropertyApiInterface;

    public function getListingTranslationApi(): ListingTranslationApiInterface;

    public function getListingVariationImageApi(): ListingVariationImageApiInterface;

    public function getListingVideoApi(): ListingVideoApiInterface;

    public function getPaymentApi(): PaymentApiInterface;

    public function getPingApi(): PingApiInterface;

    public function getShopApi(): ShopApiInterface;

    public function getShopHolidayPreferenceApi(): ShopHolidayPreferenceApiInterface;

    public function getShopListingApi(): ShopListingApiInterface;

    public function getShopProductionPartnerApi(): ShopProductionPartnerApiInterface;

    public function getShopReadinessStateDefinitionApi(): ShopReadinessStateDefinitionApiInterface;

    public function getShopReceiptApi(): ShopReceiptApiInterface;

    public function getShopReceiptTransactionApi(): ShopReceiptTransactionApiInterface;

    public function getShopReturnPolicyApi(): ShopReturnPolicyApiInterface;

    public function getShopSectionApi(): ShopSectionApiInterface;

    public function getUserAddressApi(): UserAddressApiInterface;

    public function getUserApi(): UserApiInterface;
}
