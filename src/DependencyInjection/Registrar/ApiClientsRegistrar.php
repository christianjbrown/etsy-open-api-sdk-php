<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\Api\BuyerTaxonomyApi;
use ChristianBrown\Etsy\Api\LedgerEntryApi;
use ChristianBrown\Etsy\Api\ListingBatchApi;
use ChristianBrown\Etsy\Api\ListingFileApi;
use ChristianBrown\Etsy\Api\ListingImageApi;
use ChristianBrown\Etsy\Api\ListingInventoryApi;
use ChristianBrown\Etsy\Api\ListingPersonalizationApi;
use ChristianBrown\Etsy\Api\ListingPropertyApi;
use ChristianBrown\Etsy\Api\ListingTranslationApi;
use ChristianBrown\Etsy\Api\ListingVariationImageApi;
use ChristianBrown\Etsy\Api\ListingVideoApi;
use ChristianBrown\Etsy\Api\PaymentApi;
use ChristianBrown\Etsy\Api\PingApi;
use ChristianBrown\Etsy\Api\ReviewApi;
use ChristianBrown\Etsy\Api\SellerTaxonomyApi;
use ChristianBrown\Etsy\Api\ShippingProfileApi;
use ChristianBrown\Etsy\Api\ShopApi;
use ChristianBrown\Etsy\Api\ShopHolidayPreferenceApi;
use ChristianBrown\Etsy\Api\ShopListingApi;
use ChristianBrown\Etsy\Api\ShopProductionPartnerApi;
use ChristianBrown\Etsy\Api\ShopReadinessStateDefinitionApi;
use ChristianBrown\Etsy\Api\ShopReceiptApi;
use ChristianBrown\Etsy\Api\ShopReceiptTransactionApi;
use ChristianBrown\Etsy\Api\ShopReturnPolicyApi;
use ChristianBrown\Etsy\Api\ShopSectionApi;
use ChristianBrown\Etsy\Api\UserAddressApi;
use ChristianBrown\Etsy\Api\UserApi;
use ChristianBrown\Etsy\Cache\ResponseCache;
use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Every resource client. Registered last, after every transformer and serializer chain it
 * references is already in the container.
 */
final class ApiClientsRegistrar implements ServiceRegistrarInterface
{
    private int $shopId;

    public function __construct(int $shopId)
    {
        $this->shopId = $shopId;
    }

    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_SHOP_RECEIPT_API, ShopReceiptApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_RECEIPT_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_RECEIPTS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_RECEIPT_PAGE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_CREATE_RECEIPT_SHIPMENT_REQUEST_SERIALIZER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_SHOP_RECEIPT_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_API, ShopApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOPS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_SHOP_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_LISTING_API, ShopListingApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTINGS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_CREATE_DRAFT_LISTING_REQUEST_SERIALIZER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_LISTING_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_FILE_API, ListingFileApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_FILE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_FILES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_MULTIPART_FORM_DATA_BUILDER),
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_TO_ARRAY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPLOAD_LISTING_FILE_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_IMAGE_API, ListingImageApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_IMAGE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_IMAGES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_MULTIPART_FORM_DATA_BUILDER),
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_TO_ARRAY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPLOAD_LISTING_IMAGE_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_VIDEO_API, ListingVideoApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_VIDEO_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_VIDEOS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_MULTIPART_FORM_DATA_BUILDER),
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_TO_ARRAY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPLOAD_LISTING_VIDEO_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_VARIATION_IMAGE_API, ListingVariationImageApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_VARIATION_IMAGES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_VARIATION_IMAGES_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_API, ListingInventoryApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_LISTING_INVENTORY_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_PROPERTY_API, ListingPropertyApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_PROPERTY_VALUE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_LISTING_PROPERTY_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_TRANSLATION_API, ListingTranslationApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_TRANSLATION_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_TRANSLATION_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_PERSONALIZATION_API, ListingPersonalizationApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_PERSONALIZATION_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_LISTING_PERSONALIZATION_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_USER_API, UserApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_USER_TRANSFORMER),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                ]
            );

        $container->register(EtsyInterface::SERVICE_USER_ADDRESS_API, UserAddressApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_USER_ADDRESS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_USER_ADDRESSES_TRANSFORMER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                ]
            );

        $container->register(EtsyInterface::SERVICE_PING_API, PingApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_PING_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SCOPES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_RECEIPT_TRANSACTION_API, ShopReceiptTransactionApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_TRANSACTION_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_TRANSACTIONS_TRANSFORMER),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_PAYMENT_API, PaymentApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_PAYMENTS_TRANSFORMER),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_LEDGER_ENTRY_API, LedgerEntryApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRIES_TRANSFORMER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_SECTION_API, ShopSectionApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SECTION_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SECTIONS_TRANSFORMER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_RETURN_POLICY_API, ShopReturnPolicyApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_RETURN_POLICY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_RETURN_POLICIES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_RETURN_POLICY_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_PRODUCTION_PARTNER_API, ShopProductionPartnerApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_PRODUCTION_PARTNERS_TRANSFORMER),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_HOLIDAY_PREFERENCE_API, ShopHolidayPreferenceApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_HOLIDAY_PREFERENCE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_HOLIDAY_PREFERENCES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_FORM_VALUE_ENCODER),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_READINESS_STATE_DEFINITION_API, ShopReadinessStateDefinitionApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_READINESS_STATE_DEFINITION_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_READINESS_STATE_DEFINITIONS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_CREATE_SHOP_READINESS_STATE_DEFINITION_REQUEST_SERIALIZER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_SHOP_READINESS_STATE_DEFINITION_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_REVIEW_API, ReviewApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_REVIEWS_TRANSFORMER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHIPPING_PROFILE_API, ShippingProfileApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATION_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATIONS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHIPPING_CARRIERS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_CREATE_SHOP_SHIPPING_PROFILE_REQUEST_SERIALIZER),
                    $container->getDefinition(EtsyInterface::SERVICE_CREATE_SHOP_SHIPPING_PROFILE_DESTINATION_REQUEST_SERIALIZER),
                    $container->getDefinition(EtsyInterface::SERVICE_CREATE_SHOP_SHIPPING_PROFILE_UPGRADE_REQUEST_SERIALIZER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_SHOP_SHIPPING_PROFILE_REQUEST_SERIALIZER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_SHOP_SHIPPING_PROFILE_DESTINATION_REQUEST_SERIALIZER),
                    $container->getDefinition(EtsyInterface::SERVICE_UPDATE_SHOP_SHIPPING_PROFILE_UPGRADE_REQUEST_SERIALIZER),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $container->register(EtsyInterface::SERVICE_SELLER_TAXONOMY_API, SellerTaxonomyApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_SELLER_TAXONOMY_NODES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_TAXONOMY_NODE_PROPERTIES_TRANSFORMER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                ]
            );

        $container->register(EtsyInterface::SERVICE_BUYER_TAXONOMY_API, BuyerTaxonomyApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_BUYER_TAXONOMY_NODES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_BUYER_TAXONOMY_NODE_PROPERTIES_TRANSFORMER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_BATCH_API, ListingBatchApi::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTINGS_WITH_ASSOCIATIONS_TRANSFORMER),
                    new ResponseCache(),
                    new ResponseCache(),
                    $container->getDefinition(EtsyInterface::SERVICE_CREDENTIALS),
                ]
            );
    }
}
