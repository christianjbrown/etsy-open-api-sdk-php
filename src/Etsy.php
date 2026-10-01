<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy;

use ChristianBrown\Etsy\Api\BuyerTaxonomyApiInterface;
use ChristianBrown\Etsy\Api\LedgerEntryApiInterface;
use ChristianBrown\Etsy\Api\ListingBatchApiInterface;
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
use ChristianBrown\Etsy\Api\ReviewApiInterface;
use ChristianBrown\Etsy\Api\SellerTaxonomyApiInterface;
use ChristianBrown\Etsy\Api\ShippingProfileApiInterface;
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
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class Etsy implements EtsyInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
}
