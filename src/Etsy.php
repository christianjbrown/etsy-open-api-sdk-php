<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy;

use ChristianBrown\ApiClient\ApiClient;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\BuyerTaxonomyApi;
use ChristianBrown\Etsy\Api\BuyerTaxonomyApiInterface;
use ChristianBrown\Etsy\Api\LedgerEntryApi;
use ChristianBrown\Etsy\Api\LedgerEntryApiInterface;
use ChristianBrown\Etsy\Api\ListingBatchApi;
use ChristianBrown\Etsy\Api\ListingBatchApiInterface;
use ChristianBrown\Etsy\Api\ListingFileApi;
use ChristianBrown\Etsy\Api\ListingFileApiInterface;
use ChristianBrown\Etsy\Api\ListingImageApi;
use ChristianBrown\Etsy\Api\ListingImageApiInterface;
use ChristianBrown\Etsy\Api\ListingInventoryApi;
use ChristianBrown\Etsy\Api\ListingInventoryApiInterface;
use ChristianBrown\Etsy\Api\ListingPersonalizationApi;
use ChristianBrown\Etsy\Api\ListingPersonalizationApiInterface;
use ChristianBrown\Etsy\Api\ListingPropertyApi;
use ChristianBrown\Etsy\Api\ListingPropertyApiInterface;
use ChristianBrown\Etsy\Api\ListingTranslationApi;
use ChristianBrown\Etsy\Api\ListingTranslationApiInterface;
use ChristianBrown\Etsy\Api\ListingVariationImageApi;
use ChristianBrown\Etsy\Api\ListingVariationImageApiInterface;
use ChristianBrown\Etsy\Api\ListingVideoApi;
use ChristianBrown\Etsy\Api\ListingVideoApiInterface;
use ChristianBrown\Etsy\Api\PaymentApi;
use ChristianBrown\Etsy\Api\PaymentApiInterface;
use ChristianBrown\Etsy\Api\PingApi;
use ChristianBrown\Etsy\Api\PingApiInterface;
use ChristianBrown\Etsy\Api\ReviewApi;
use ChristianBrown\Etsy\Api\ReviewApiInterface;
use ChristianBrown\Etsy\Api\SellerTaxonomyApi;
use ChristianBrown\Etsy\Api\SellerTaxonomyApiInterface;
use ChristianBrown\Etsy\Api\ShippingProfileApi;
use ChristianBrown\Etsy\Api\ShippingProfileApiInterface;
use ChristianBrown\Etsy\Api\ShopApi;
use ChristianBrown\Etsy\Api\ShopApiInterface;
use ChristianBrown\Etsy\Api\ShopHolidayPreferenceApi;
use ChristianBrown\Etsy\Api\ShopHolidayPreferenceApiInterface;
use ChristianBrown\Etsy\Api\ShopListingApi;
use ChristianBrown\Etsy\Api\ShopListingApiInterface;
use ChristianBrown\Etsy\Api\ShopProductionPartnerApi;
use ChristianBrown\Etsy\Api\ShopProductionPartnerApiInterface;
use ChristianBrown\Etsy\Api\ShopReadinessStateDefinitionApi;
use ChristianBrown\Etsy\Api\ShopReadinessStateDefinitionApiInterface;
use ChristianBrown\Etsy\Api\ShopReceiptApi;
use ChristianBrown\Etsy\Api\ShopReceiptApiInterface;
use ChristianBrown\Etsy\Api\ShopReceiptTransactionApi;
use ChristianBrown\Etsy\Api\ShopReceiptTransactionApiInterface;
use ChristianBrown\Etsy\Api\ShopReturnPolicyApi;
use ChristianBrown\Etsy\Api\ShopReturnPolicyApiInterface;
use ChristianBrown\Etsy\Api\ShopSectionApi;
use ChristianBrown\Etsy\Api\ShopSectionApiInterface;
use ChristianBrown\Etsy\Api\UserAddressApi;
use ChristianBrown\Etsy\Api\UserAddressApiInterface;
use ChristianBrown\Etsy\Api\UserApi;
use ChristianBrown\Etsy\Api\UserApiInterface;
use ChristianBrown\Etsy\Auth\Credentials;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertiesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertyTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodeTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyScalesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyScaleTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyValueTransformer;
use ChristianBrown\Etsy\Transformer\ListingBuyerPriceTransformer;
use ChristianBrown\Etsy\Transformer\ListingFilesTransformer;
use ChristianBrown\Etsy\Transformer\ListingFileTransformer;
use ChristianBrown\Etsy\Transformer\ListingImagesTransformer;
use ChristianBrown\Etsy\Transformer\ListingImageTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingsTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductsTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryTransformer;
use ChristianBrown\Etsy\Transformer\ListingPersonalizationTransformer;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\ListingPropertyValueTransformer;
use ChristianBrown\Etsy\Transformer\ListingsTransformer;
use ChristianBrown\Etsy\Transformer\ListingsWithAssociationsTransformer;
use ChristianBrown\Etsy\Transformer\ListingTransformer;
use ChristianBrown\Etsy\Transformer\ListingTranslationTransformer;
use ChristianBrown\Etsy\Transformer\ListingVariationImagesTransformer;
use ChristianBrown\Etsy\Transformer\ListingVariationImageTransformer;
use ChristianBrown\Etsy\Transformer\ListingVideosTransformer;
use ChristianBrown\Etsy\Transformer\ListingVideoTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsTransformer;
use ChristianBrown\Etsy\Transformer\MoneyTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntriesTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntryTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentTransformer;
use ChristianBrown\Etsy\Transformer\PaymentsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionsTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionsTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionTransformer;
use ChristianBrown\Etsy\Transformer\PingTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\RefundsTransformer;
use ChristianBrown\Etsy\Transformer\RefundTransformer;
use ChristianBrown\Etsy\Transformer\ReviewsTransformer;
use ChristianBrown\Etsy\Transformer\ReviewTransformer;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodesTransformer;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodeTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentsTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarrierMailClassesTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarrierMailClassTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarriersTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarrierTransformer;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferencesTransformer;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferenceTransformer;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnersTransformer;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnerTransformer;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionsTransformer;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionTransformer;
use ChristianBrown\Etsy\Transformer\ShopReturnPoliciesTransformer;
use ChristianBrown\Etsy\Transformer\ShopReturnPolicyTransformer;
use ChristianBrown\Etsy\Transformer\ShopSectionsTransformer;
use ChristianBrown\Etsy\Transformer\ShopSectionTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationsTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfilesTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradesTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradeTransformer;
use ChristianBrown\Etsy\Transformer\ShopsTransformer;
use ChristianBrown\Etsy\Transformer\ShopTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertiesTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertyTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyScalesTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyScaleTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyValueTransformer;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationTransformer;
use ChristianBrown\Etsy\Transformer\UserAddressesTransformer;
use ChristianBrown\Etsy\Transformer\UserAddressTransformer;
use ChristianBrown\Etsy\Transformer\UserTransformer;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\OAuth2Client\RefreshTokenManager;
use ChristianBrown\OAuth2Client\Transformer\AccessTokenTransformer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class Etsy implements EtsyInterface
{
    private KeyValueStoreInterface $accessTokenStore;
    private ContainerBuilder $container;
    private string $key;
    private KeyValueStoreInterface $refreshTokenStore;
    private int $shopId;

    public function __construct(int $shopId, string $key, KeyValueStoreInterface $accessTokenStore, KeyValueStoreInterface $refreshTokenStore)
    {
        $this->shopId = $shopId;
        $this->key = $key;
        $this->accessTokenStore = $accessTokenStore;
        $this->refreshTokenStore = $refreshTokenStore;
        $this->container = new ContainerBuilder();
        $this->init();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getBuyerTaxonomyApi(): BuyerTaxonomyApiInterface
    {
        /**
         * @var BuyerTaxonomyApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_BUYER_TAXONOMY_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getLedgerEntryApi(): LedgerEntryApiInterface
    {
        /**
         * @var LedgerEntryApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_LEDGER_ENTRY_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListingBatchApi(): ListingBatchApiInterface
    {
        /**
         * @var ListingBatchApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_LISTING_BATCH_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListingFileApi(): ListingFileApiInterface
    {
        /**
         * @var ListingFileApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_LISTING_FILE_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListingImageApi(): ListingImageApiInterface
    {
        /**
         * @var ListingImageApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_LISTING_IMAGE_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListingInventoryApi(): ListingInventoryApiInterface
    {
        /**
         * @var ListingInventoryApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_LISTING_INVENTORY_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListingPersonalizationApi(): ListingPersonalizationApiInterface
    {
        /**
         * @var ListingPersonalizationApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_LISTING_PERSONALIZATION_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListingPropertyApi(): ListingPropertyApiInterface
    {
        /**
         * @var ListingPropertyApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_LISTING_PROPERTY_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListingTranslationApi(): ListingTranslationApiInterface
    {
        /**
         * @var ListingTranslationApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_LISTING_TRANSLATION_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListingVariationImageApi(): ListingVariationImageApiInterface
    {
        /**
         * @var ListingVariationImageApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_LISTING_VARIATION_IMAGE_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListingVideoApi(): ListingVideoApiInterface
    {
        /**
         * @var ListingVideoApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_LISTING_VIDEO_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getPaymentApi(): PaymentApiInterface
    {
        /**
         * @var PaymentApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_PAYMENT_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getPingApi(): PingApiInterface
    {
        /**
         * @var PingApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_PING_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getReviewApi(): ReviewApiInterface
    {
        /**
         * @var ReviewApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_REVIEW_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getSellerTaxonomyApi(): SellerTaxonomyApiInterface
    {
        /**
         * @var SellerTaxonomyApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SELLER_TAXONOMY_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShippingProfileApi(): ShippingProfileApiInterface
    {
        /**
         * @var ShippingProfileApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHIPPING_PROFILE_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopApi(): ShopApiInterface
    {
        /**
         * @var ShopApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopHolidayPreferenceApi(): ShopHolidayPreferenceApiInterface
    {
        /**
         * @var ShopHolidayPreferenceApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_HOLIDAY_PREFERENCE_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopListingApi(): ShopListingApiInterface
    {
        /**
         * @var ShopListingApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_LISTING_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopProductionPartnerApi(): ShopProductionPartnerApiInterface
    {
        /**
         * @var ShopProductionPartnerApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_PRODUCTION_PARTNER_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopReadinessStateDefinitionApi(): ShopReadinessStateDefinitionApiInterface
    {
        /**
         * @var ShopReadinessStateDefinitionApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_READINESS_STATE_DEFINITION_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopReceiptApi(): ShopReceiptApiInterface
    {
        /**
         * @var ShopReceiptApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_RECEIPT_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopReceiptTransactionApi(): ShopReceiptTransactionApiInterface
    {
        /**
         * @var ShopReceiptTransactionApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_RECEIPT_TRANSACTION_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopReturnPolicyApi(): ShopReturnPolicyApiInterface
    {
        /**
         * @var ShopReturnPolicyApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_RETURN_POLICY_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopSectionApi(): ShopSectionApiInterface
    {
        /**
         * @var ShopSectionApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_SECTION_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUserAddressApi(): UserAddressApiInterface
    {
        /**
         * @var UserAddressApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_USER_ADDRESS_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUserApi(): UserApiInterface
    {
        /**
         * @var UserApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_USER_API);

        return $service;
    }

    private function init(): void
    {
        // Registration order matters: a service must be registered before another
        // service wires a reference to its definition, so core comes first and the
        // API clients (which reference every transformer chain) come last.
        $this->registerCore();
        $this->registerReceiptTransformers();
        $this->registerListingTransformers();
        $this->registerListingInventoryTransformers();
        $this->registerListingMediaTransformers();
        $this->registerListingTranslationTransformers();
        $this->registerListingPersonalizationTransformers();
        $this->registerShopTransformers();
        $this->registerShopConfigurationTransformers();
        $this->registerShippingProfileTransformers();
        $this->registerUserTransformers();
        $this->registerUserAddressTransformers();
        $this->registerPingTransformers();
        $this->registerPaymentAdjustmentTransformers();
        $this->registerPaymentTransformers();
        $this->registerLedgerTransformers();
        $this->registerReviewTransformers();
        $this->registerSellerTaxonomyTransformers();
        $this->registerBuyerTaxonomyTransformers();
        $this->registerListingWithAssociationsTransformers();
        $this->registerApiClients();
    }

    private function registerApiClients(): void
    {
        $this->container->register(self::SERVICE_SHOP_RECEIPT_API, ShopReceiptApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_RECEIPT_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_RECEIPTS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_SHOP_API, ShopApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_SHOP_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOPS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_SHOP_LISTING_API, ShopListingApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_LISTING_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTINGS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_LISTING_FILE_API, ListingFileApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_LISTING_FILE_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_FILES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_LISTING_IMAGE_API, ListingImageApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_LISTING_IMAGE_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_IMAGES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_VIDEO_API, ListingVideoApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_LISTING_VIDEO_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_VIDEOS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_VARIATION_IMAGE_API, ListingVariationImageApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_LISTING_VARIATION_IMAGES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_LISTING_INVENTORY_API, ListingInventoryApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_LISTING_INVENTORY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_INVENTORY_PRODUCT_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_PROPERTY_API, ListingPropertyApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_LISTING_PROPERTY_VALUE_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_LISTING_TRANSLATION_API, ListingTranslationApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_LISTING_TRANSLATION_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_LISTING_PERSONALIZATION_API, ListingPersonalizationApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_LISTING_PERSONALIZATION_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_USER_API, UserApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_USER_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_USER_ADDRESS_API, UserAddressApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_USER_ADDRESS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_USER_ADDRESSES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_PING_API, PingApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_PING_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_SHOP_RECEIPT_TRANSACTION_API, ShopReceiptTransactionApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_TRANSACTION_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_TRANSACTIONS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_PAYMENT_API, PaymentApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_PAYMENTS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_LEDGER_ENTRY_API, LedgerEntryApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRIES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_SHOP_SECTION_API, ShopSectionApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_SHOP_SECTION_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOP_SECTIONS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_SHOP_RETURN_POLICY_API, ShopReturnPolicyApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_SHOP_RETURN_POLICY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOP_RETURN_POLICIES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_SHOP_PRODUCTION_PARTNER_API, ShopProductionPartnerApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_SHOP_PRODUCTION_PARTNERS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_SHOP_HOLIDAY_PREFERENCE_API, ShopHolidayPreferenceApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_SHOP_HOLIDAY_PREFERENCES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_SHOP_READINESS_STATE_DEFINITION_API, ShopReadinessStateDefinitionApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_SHOP_READINESS_STATE_DEFINITION_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOP_READINESS_STATE_DEFINITIONS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_REVIEW_API, ReviewApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_REVIEWS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_SHIPPING_PROFILE_API, ShippingProfileApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_SHOP_SHIPPING_PROFILE_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOP_SHIPPING_PROFILES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATIONS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHIPPING_CARRIERS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_SELLER_TAXONOMY_API, SellerTaxonomyApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_SELLER_TAXONOMY_NODES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_TAXONOMY_NODE_PROPERTIES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_BUYER_TAXONOMY_API, BuyerTaxonomyApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_BUYER_TAXONOMY_NODES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_BUYER_TAXONOMY_NODE_PROPERTIES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_BATCH_API, ListingBatchApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_LISTINGS_WITH_ASSOCIATIONS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );
    }

    private function registerBuyerTaxonomyTransformers(): void
    {
        $this->container->register(self::SERVICE_BUYER_TAXONOMY_NODE_TRANSFORMER, BuyerTaxonomyNodeTransformer::class);
        $this->container->register(self::SERVICE_BUYER_TAXONOMY_NODES_TRANSFORMER, BuyerTaxonomyNodesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_BUYER_TAXONOMY_NODE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_BUYER_TAXONOMY_PROPERTY_SCALE_TRANSFORMER, BuyerTaxonomyPropertyScaleTransformer::class);
        $this->container->register(self::SERVICE_BUYER_TAXONOMY_PROPERTY_SCALES_TRANSFORMER, BuyerTaxonomyPropertyScalesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_BUYER_TAXONOMY_PROPERTY_SCALE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_BUYER_TAXONOMY_PROPERTY_VALUE_TRANSFORMER, BuyerTaxonomyPropertyValueTransformer::class);
        $this->container->register(self::SERVICE_BUYER_TAXONOMY_PROPERTY_VALUES_TRANSFORMER, BuyerTaxonomyPropertyValuesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_BUYER_TAXONOMY_PROPERTY_VALUE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_BUYER_TAXONOMY_NODE_PROPERTY_TRANSFORMER, BuyerTaxonomyNodePropertyTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_BUYER_TAXONOMY_PROPERTY_SCALES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_BUYER_TAXONOMY_PROPERTY_VALUES_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_BUYER_TAXONOMY_NODE_PROPERTIES_TRANSFORMER, BuyerTaxonomyNodePropertiesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_BUYER_TAXONOMY_NODE_PROPERTY_TRANSFORMER),
                ]
            );
    }

    private function registerCore(): void
    {
        $this->container->register(self::SERVICE_API_CLIENT, ApiClient::class);
        $this->container->register(self::SERVICE_JSON_API_REQUEST_SENDER, JsonApiRequestSenderInterface::class)
            ->setFactory([new Reference(self::SERVICE_API_CLIENT), 'getJsonApiRequestSender']);

        $this->container->register(self::SERVICE_ACCESS_TOKEN_TRANSFORMER, AccessTokenTransformer::class);

        $this->container->register(self::SERVICE_REFRESH_TOKEN_MANAGER, RefreshTokenManager::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->accessTokenStore,
                    $this->refreshTokenStore,
                    $this->container->getDefinition(self::SERVICE_ACCESS_TOKEN_TRANSFORMER),
                    self::OAUTH_TOKEN_URL,
                ]
            );

        $this->container->register(self::SERVICE_CREDENTIALS, Credentials::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_REFRESH_TOKEN_MANAGER),
                    $this->key,
                ]
            );
    }

    private function registerLedgerTransformers(): void
    {
        $this->container->register(self::SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRY_TRANSFORMER, PaymentAccountLedgerEntryTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_PAYMENT_ADJUSTMENTS_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRIES_TRANSFORMER, PaymentAccountLedgerEntriesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRY_TRANSFORMER),
                ]
            );
    }

    private function registerListingInventoryTransformers(): void
    {
        $this->container->register(self::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_TRANSFORMER, ListingInventoryProductOfferingTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERINGS_TRANSFORMER, ListingInventoryProductOfferingsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_INVENTORY_PRODUCT_TRANSFORMER, ListingInventoryProductTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERINGS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_LISTING_INVENTORY_PRODUCTS_TRANSFORMER, ListingInventoryProductsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_INVENTORY_PRODUCT_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_INVENTORY_TRANSFORMER, ListingInventoryTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_INVENTORY_PRODUCTS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_TRANSFORMER),
                ]
            );
    }

    private function registerListingMediaTransformers(): void
    {
        $this->container->register(self::SERVICE_LISTING_FILE_TRANSFORMER, ListingFileTransformer::class);
        $this->container->register(self::SERVICE_LISTING_FILES_TRANSFORMER, ListingFilesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_FILE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_IMAGE_TRANSFORMER, ListingImageTransformer::class);
        $this->container->register(self::SERVICE_LISTING_IMAGES_TRANSFORMER, ListingImagesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_IMAGE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_VIDEO_TRANSFORMER, ListingVideoTransformer::class);
        $this->container->register(self::SERVICE_LISTING_VIDEOS_TRANSFORMER, ListingVideosTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_VIDEO_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_VARIATION_IMAGE_TRANSFORMER, ListingVariationImageTransformer::class);
        $this->container->register(self::SERVICE_LISTING_VARIATION_IMAGES_TRANSFORMER, ListingVariationImagesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_VARIATION_IMAGE_TRANSFORMER),
                ]
            );
    }

    private function registerListingPersonalizationTransformers(): void
    {
        $this->container->register(self::SERVICE_PERSONALIZATION_QUESTION_OPTION_TRANSFORMER, PersonalizationQuestionOptionTransformer::class);
        $this->container->register(self::SERVICE_PERSONALIZATION_QUESTION_OPTIONS_TRANSFORMER, PersonalizationQuestionOptionsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_PERSONALIZATION_QUESTION_OPTION_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_PERSONALIZATION_QUESTION_TRANSFORMER, PersonalizationQuestionTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_PERSONALIZATION_QUESTION_OPTIONS_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_PERSONALIZATION_QUESTIONS_TRANSFORMER, PersonalizationQuestionsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_PERSONALIZATION_QUESTION_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_PERSONALIZATION_TRANSFORMER, ListingPersonalizationTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_PERSONALIZATION_QUESTIONS_TRANSFORMER),
                ]
            );
    }

    private function registerListingTransformers(): void
    {
        $this->container->register(self::SERVICE_LISTING_TRANSFORMER, ListingTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_LISTINGS_TRANSFORMER, ListingsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_TRANSFORMER),
                ]
            );
    }

    private function registerListingTranslationTransformers(): void
    {
        $this->container->register(self::SERVICE_LISTING_TRANSLATION_TRANSFORMER, ListingTranslationTransformer::class);
    }

    private function registerListingWithAssociationsTransformers(): void
    {
        $this->container->register(self::SERVICE_LISTING_BUYER_PRICE_TRANSFORMER, ListingBuyerPriceTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_WITH_ASSOCIATIONS_TRANSFORMER, ListingWithAssociationsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_BUYER_PRICE_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_IMAGES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_INVENTORY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_PERSONALIZATION_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_TRANSLATION_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_VIDEOS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOP_PRODUCTION_PARTNERS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOP_SHIPPING_PROFILE_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOP_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_USER_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_LISTINGS_WITH_ASSOCIATIONS_TRANSFORMER, ListingsWithAssociationsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_WITH_ASSOCIATIONS_TRANSFORMER),
                ]
            );
    }

    private function registerPaymentAdjustmentTransformers(): void
    {
        $this->container->register(self::SERVICE_PAYMENT_ADJUSTMENT_ITEM_TRANSFORMER, PaymentAdjustmentItemTransformer::class);
        $this->container->register(self::SERVICE_PAYMENT_ADJUSTMENT_ITEMS_TRANSFORMER, PaymentAdjustmentItemsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_PAYMENT_ADJUSTMENT_ITEM_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_PAYMENT_ADJUSTMENT_TRANSFORMER, PaymentAdjustmentTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_PAYMENT_ADJUSTMENT_ITEMS_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_PAYMENT_ADJUSTMENTS_TRANSFORMER, PaymentAdjustmentsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_PAYMENT_ADJUSTMENT_TRANSFORMER),
                ]
            );
    }

    private function registerPaymentTransformers(): void
    {
        $this->container->register(self::SERVICE_PAYMENT_TRANSFORMER, PaymentTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_PAYMENT_ADJUSTMENTS_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_PAYMENTS_TRANSFORMER, PaymentsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_PAYMENT_TRANSFORMER),
                ]
            );
    }

    private function registerPingTransformers(): void
    {
        $this->container->register(self::SERVICE_PING_TRANSFORMER, PingTransformer::class);
    }

    private function registerReceiptTransformers(): void
    {
        $this->container->register(self::SERVICE_MONEY_TRANSFORMER, MoneyTransformer::class);

        $this->container->register(self::SERVICE_SHIPMENT_TRANSFORMER, ShipmentTransformer::class);
        $this->container->register(self::SERVICE_SHIPMENTS_TRANSFORMER, ShipmentsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHIPMENT_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_REFUND_TRANSFORMER, RefundTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_REFUNDS_TRANSFORMER, RefundsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_REFUND_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_TRANSACTION_VARIATION_TRANSFORMER, TransactionVariationTransformer::class);
        $this->container->register(self::SERVICE_TRANSACTION_VARIATIONS_TRANSFORMER, TransactionVariationsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_TRANSACTION_VARIATION_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_PROPERTY_VALUE_TRANSFORMER, ListingPropertyValueTransformer::class);
        $this->container->register(self::SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER, ListingPropertyValuesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_PROPERTY_VALUE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_TRANSACTION_TRANSFORMER, TransactionTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_TRANSACTION_VARIATIONS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_TRANSACTIONS_TRANSFORMER, TransactionsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_TRANSACTION_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_RECEIPT_TRANSFORMER, ReceiptTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_TRANSACTIONS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_REFUNDS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHIPMENTS_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_RECEIPTS_TRANSFORMER, ReceiptsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_RECEIPT_TRANSFORMER),
                ]
            );
    }

    private function registerReviewTransformers(): void
    {
        $this->container->register(self::SERVICE_REVIEW_TRANSFORMER, ReviewTransformer::class);
        $this->container->register(self::SERVICE_REVIEWS_TRANSFORMER, ReviewsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_REVIEW_TRANSFORMER),
                ]
            );
    }

    private function registerSellerTaxonomyTransformers(): void
    {
        $this->container->register(self::SERVICE_SELLER_TAXONOMY_NODE_TRANSFORMER, SellerTaxonomyNodeTransformer::class);
        $this->container->register(self::SERVICE_SELLER_TAXONOMY_NODES_TRANSFORMER, SellerTaxonomyNodesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SELLER_TAXONOMY_NODE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_TAXONOMY_PROPERTY_SCALE_TRANSFORMER, TaxonomyPropertyScaleTransformer::class);
        $this->container->register(self::SERVICE_TAXONOMY_PROPERTY_SCALES_TRANSFORMER, TaxonomyPropertyScalesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_TAXONOMY_PROPERTY_SCALE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_TAXONOMY_PROPERTY_VALUE_TRANSFORMER, TaxonomyPropertyValueTransformer::class);
        $this->container->register(self::SERVICE_TAXONOMY_PROPERTY_VALUES_TRANSFORMER, TaxonomyPropertyValuesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_TAXONOMY_PROPERTY_VALUE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_TAXONOMY_NODE_PROPERTY_TRANSFORMER, TaxonomyNodePropertyTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_TAXONOMY_PROPERTY_SCALES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_TAXONOMY_PROPERTY_VALUES_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_TAXONOMY_NODE_PROPERTIES_TRANSFORMER, TaxonomyNodePropertiesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_TAXONOMY_NODE_PROPERTY_TRANSFORMER),
                ]
            );
    }

    private function registerShippingProfileTransformers(): void
    {
        $this->container->register(self::SERVICE_SHIPPING_CARRIER_MAIL_CLASS_TRANSFORMER, ShippingCarrierMailClassTransformer::class);
        $this->container->register(self::SERVICE_SHIPPING_CARRIER_MAIL_CLASSES_TRANSFORMER, ShippingCarrierMailClassesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHIPPING_CARRIER_MAIL_CLASS_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_SHIPPING_CARRIER_TRANSFORMER, ShippingCarrierTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHIPPING_CARRIER_MAIL_CLASSES_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_SHIPPING_CARRIERS_TRANSFORMER, ShippingCarriersTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHIPPING_CARRIER_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATION_TRANSFORMER, ShopShippingProfileDestinationTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATIONS_TRANSFORMER, ShopShippingProfileDestinationsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATION_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADE_TRANSFORMER, ShopShippingProfileUpgradeTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADES_TRANSFORMER, ShopShippingProfileUpgradesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_SHOP_SHIPPING_PROFILE_TRANSFORMER, ShopShippingProfileTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATIONS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADES_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_SHOP_SHIPPING_PROFILES_TRANSFORMER, ShopShippingProfilesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_SHIPPING_PROFILE_TRANSFORMER),
                ]
            );
    }

    private function registerShopConfigurationTransformers(): void
    {
        $this->container->register(self::SERVICE_SHOP_SECTION_TRANSFORMER, ShopSectionTransformer::class);
        $this->container->register(self::SERVICE_SHOP_SECTIONS_TRANSFORMER, ShopSectionsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_SECTION_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_SHOP_RETURN_POLICY_TRANSFORMER, ShopReturnPolicyTransformer::class);
        $this->container->register(self::SERVICE_SHOP_RETURN_POLICIES_TRANSFORMER, ShopReturnPoliciesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_RETURN_POLICY_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_SHOP_PRODUCTION_PARTNER_TRANSFORMER, ShopProductionPartnerTransformer::class);
        $this->container->register(self::SERVICE_SHOP_PRODUCTION_PARTNERS_TRANSFORMER, ShopProductionPartnersTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_PRODUCTION_PARTNER_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_SHOP_HOLIDAY_PREFERENCE_TRANSFORMER, ShopHolidayPreferenceTransformer::class);
        $this->container->register(self::SERVICE_SHOP_HOLIDAY_PREFERENCES_TRANSFORMER, ShopHolidayPreferencesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_HOLIDAY_PREFERENCE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_SHOP_READINESS_STATE_DEFINITION_TRANSFORMER, ShopReadinessStateDefinitionTransformer::class);
        $this->container->register(self::SERVICE_SHOP_READINESS_STATE_DEFINITIONS_TRANSFORMER, ShopReadinessStateDefinitionsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_READINESS_STATE_DEFINITION_TRANSFORMER),
                ]
            );
    }

    private function registerShopTransformers(): void
    {
        $this->container->register(self::SERVICE_SHOP_TRANSFORMER, ShopTransformer::class);
        $this->container->register(self::SERVICE_SHOPS_TRANSFORMER, ShopsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_TRANSFORMER),
                ]
            );
    }

    private function registerUserAddressTransformers(): void
    {
        $this->container->register(self::SERVICE_USER_ADDRESS_TRANSFORMER, UserAddressTransformer::class);
        $this->container->register(self::SERVICE_USER_ADDRESSES_TRANSFORMER, UserAddressesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_USER_ADDRESS_TRANSFORMER),
                ]
            );
    }

    private function registerUserTransformers(): void
    {
        $this->container->register(self::SERVICE_USER_TRANSFORMER, UserTransformer::class);
    }
}
