<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests;

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
use ChristianBrown\Etsy\Etsy;
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
use ChristianBrown\Etsy\Transformer\ListingImagesTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingsTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductsTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryTransformer;
use ChristianBrown\Etsy\Transformer\ListingPersonalizationTransformer;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\ListingsTransformer;
use ChristianBrown\Etsy\Transformer\ListingsWithAssociationsTransformer;
use ChristianBrown\Etsy\Transformer\ListingTransformer;
use ChristianBrown\Etsy\Transformer\ListingVariationImagesTransformer;
use ChristianBrown\Etsy\Transformer\ListingVideosTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntriesTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntryTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentTransformer;
use ChristianBrown\Etsy\Transformer\PaymentsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionsTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionsTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionTransformer;
use ChristianBrown\Etsy\Transformer\PingTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptPageTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\RefundsTransformer;
use ChristianBrown\Etsy\Transformer\RefundTransformer;
use ChristianBrown\Etsy\Transformer\ReviewsTransformer;
use ChristianBrown\Etsy\Transformer\ReviewTransformer;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodesTransformer;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodeTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentsTransformer;
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
use ChristianBrown\Etsy\Transformer\UserAddressesTransformer;
use ChristianBrown\Etsy\Transformer\UserAddressTransformer;
use ChristianBrown\Etsy\Transformer\UserTransformer;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\KeyValueStore\TtlAwareKeyValueStoreInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Etsy::class)]
#[UsesClass(BuyerTaxonomyApi::class)]
#[UsesClass(SellerTaxonomyApi::class)]
#[UsesClass(ListingFileApi::class)]
#[UsesClass(ListingImageApi::class)]
#[UsesClass(ListingInventoryApi::class)]
#[UsesClass(ListingPersonalizationApi::class)]
#[UsesClass(ListingPropertyApi::class)]
#[UsesClass(ListingTranslationApi::class)]
#[UsesClass(ListingVariationImageApi::class)]
#[UsesClass(ListingVideoApi::class)]
#[UsesClass(PingApi::class)]
#[UsesClass(ReviewApi::class)]
#[UsesClass(ShopApi::class)]
#[UsesClass(ShopHolidayPreferenceApi::class)]
#[UsesClass(ShopListingApi::class)]
#[UsesClass(ShopProductionPartnerApi::class)]
#[UsesClass(ShopReadinessStateDefinitionApi::class)]
#[UsesClass(ShopReceiptApi::class)]
#[UsesClass(ShopReceiptTransactionApi::class)]
#[UsesClass(ShopReturnPolicyApi::class)]
#[UsesClass(ShippingProfileApi::class)]
#[UsesClass(ShopSectionApi::class)]
#[UsesClass(PaymentApi::class)]
#[UsesClass(LedgerEntryApi::class)]
#[UsesClass(ListingBatchApi::class)]
#[UsesClass(UserApi::class)]
#[UsesClass(UserAddressApi::class)]
#[UsesClass(Credentials::class)]
#[UsesClass(ListingBuyerPriceTransformer::class)]
#[UsesClass(ListingFilesTransformer::class)]
#[UsesClass(ListingImagesTransformer::class)]
#[UsesClass(ListingInventoryProductOfferingTransformer::class)]
#[UsesClass(ListingInventoryProductOfferingsTransformer::class)]
#[UsesClass(ListingInventoryProductTransformer::class)]
#[UsesClass(ListingInventoryProductsTransformer::class)]
#[UsesClass(ListingInventoryTransformer::class)]
#[UsesClass(ListingPersonalizationTransformer::class)]
#[UsesClass(ListingPropertyValuesTransformer::class)]
#[UsesClass(ListingTransformer::class)]
#[UsesClass(ListingsTransformer::class)]
#[UsesClass(ListingVariationImagesTransformer::class)]
#[UsesClass(ListingVideosTransformer::class)]
#[UsesClass(ListingWithAssociationsTransformer::class)]
#[UsesClass(ListingsWithAssociationsTransformer::class)]
#[UsesClass(PersonalizationQuestionOptionsTransformer::class)]
#[UsesClass(PersonalizationQuestionsTransformer::class)]
#[UsesClass(PersonalizationQuestionTransformer::class)]
#[UsesClass(PingTransformer::class)]
#[UsesClass(ReceiptPageTransformer::class)]
#[UsesClass(ReceiptTransformer::class)]
#[UsesClass(ReceiptsTransformer::class)]
#[UsesClass(RefundTransformer::class)]
#[UsesClass(RefundsTransformer::class)]
#[UsesClass(ReviewTransformer::class)]
#[UsesClass(ReviewsTransformer::class)]
#[UsesClass(ShipmentsTransformer::class)]
#[UsesClass(ShopTransformer::class)]
#[UsesClass(ShopsTransformer::class)]
#[UsesClass(ShopHolidayPreferenceTransformer::class)]
#[UsesClass(ShopHolidayPreferencesTransformer::class)]
#[UsesClass(ShopProductionPartnerTransformer::class)]
#[UsesClass(ShopProductionPartnersTransformer::class)]
#[UsesClass(ShopReadinessStateDefinitionTransformer::class)]
#[UsesClass(ShopReadinessStateDefinitionsTransformer::class)]
#[UsesClass(ShopReturnPolicyTransformer::class)]
#[UsesClass(ShopReturnPoliciesTransformer::class)]
#[UsesClass(ShopSectionTransformer::class)]
#[UsesClass(ShopSectionsTransformer::class)]
#[UsesClass(ShippingCarrierMailClassTransformer::class)]
#[UsesClass(ShippingCarrierMailClassesTransformer::class)]
#[UsesClass(ShippingCarrierTransformer::class)]
#[UsesClass(ShippingCarriersTransformer::class)]
#[UsesClass(ShopShippingProfileDestinationTransformer::class)]
#[UsesClass(ShopShippingProfileDestinationsTransformer::class)]
#[UsesClass(ShopShippingProfileUpgradeTransformer::class)]
#[UsesClass(ShopShippingProfileUpgradesTransformer::class)]
#[UsesClass(ShopShippingProfileTransformer::class)]
#[UsesClass(ShopShippingProfilesTransformer::class)]
#[UsesClass(TransactionTransformer::class)]
#[UsesClass(TransactionsTransformer::class)]
#[UsesClass(TransactionVariationsTransformer::class)]
#[UsesClass(PaymentTransformer::class)]
#[UsesClass(PaymentsTransformer::class)]
#[UsesClass(PaymentAccountLedgerEntryTransformer::class)]
#[UsesClass(PaymentAccountLedgerEntriesTransformer::class)]
#[UsesClass(PaymentAdjustmentItemTransformer::class)]
#[UsesClass(PaymentAdjustmentItemsTransformer::class)]
#[UsesClass(PaymentAdjustmentTransformer::class)]
#[UsesClass(PaymentAdjustmentsTransformer::class)]
#[UsesClass(UserTransformer::class)]
#[UsesClass(UserAddressTransformer::class)]
#[UsesClass(UserAddressesTransformer::class)]
#[UsesClass(SellerTaxonomyNodeTransformer::class)]
#[UsesClass(SellerTaxonomyNodesTransformer::class)]
#[UsesClass(TaxonomyNodePropertyTransformer::class)]
#[UsesClass(TaxonomyNodePropertiesTransformer::class)]
#[UsesClass(TaxonomyPropertyScaleTransformer::class)]
#[UsesClass(TaxonomyPropertyScalesTransformer::class)]
#[UsesClass(TaxonomyPropertyValueTransformer::class)]
#[UsesClass(TaxonomyPropertyValuesTransformer::class)]
#[UsesClass(BuyerTaxonomyNodeTransformer::class)]
#[UsesClass(BuyerTaxonomyNodesTransformer::class)]
#[UsesClass(BuyerTaxonomyNodePropertyTransformer::class)]
#[UsesClass(BuyerTaxonomyNodePropertiesTransformer::class)]
#[UsesClass(BuyerTaxonomyPropertyScaleTransformer::class)]
#[UsesClass(BuyerTaxonomyPropertyScalesTransformer::class)]
#[UsesClass(BuyerTaxonomyPropertyValueTransformer::class)]
#[UsesClass(BuyerTaxonomyPropertyValuesTransformer::class)]
final class EtsyTest extends TestCase
{
    public function testGetBuyerTaxonomyApi(): void
    {
        self::assertInstanceOf(BuyerTaxonomyApiInterface::class, $this->buildEtsy()->getBuyerTaxonomyApi());
    }

    public function testGetBuyerTaxonomyApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getBuyerTaxonomyApi(), $etsy->getBuyerTaxonomyApi());
    }

    public function testGetLedgerEntryApi(): void
    {
        self::assertInstanceOf(LedgerEntryApiInterface::class, $this->buildEtsy()->getLedgerEntryApi());
    }

    public function testGetLedgerEntryApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getLedgerEntryApi(), $etsy->getLedgerEntryApi());
    }

    public function testGetListingBatchApi(): void
    {
        self::assertInstanceOf(ListingBatchApiInterface::class, $this->buildEtsy()->getListingBatchApi());
    }

    public function testGetListingBatchApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getListingBatchApi(), $etsy->getListingBatchApi());
    }

    public function testGetListingFileApi(): void
    {
        self::assertInstanceOf(ListingFileApiInterface::class, $this->buildEtsy()->getListingFileApi());
    }

    public function testGetListingFileApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getListingFileApi(), $etsy->getListingFileApi());
    }

    public function testGetListingImageApi(): void
    {
        self::assertInstanceOf(ListingImageApiInterface::class, $this->buildEtsy()->getListingImageApi());
    }

    public function testGetListingImageApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getListingImageApi(), $etsy->getListingImageApi());
    }

    public function testGetListingInventoryApi(): void
    {
        self::assertInstanceOf(ListingInventoryApiInterface::class, $this->buildEtsy()->getListingInventoryApi());
    }

    public function testGetListingInventoryApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getListingInventoryApi(), $etsy->getListingInventoryApi());
    }

    public function testGetListingPersonalizationApi(): void
    {
        self::assertInstanceOf(ListingPersonalizationApiInterface::class, $this->buildEtsy()->getListingPersonalizationApi());
    }

    public function testGetListingPersonalizationApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getListingPersonalizationApi(), $etsy->getListingPersonalizationApi());
    }

    public function testGetListingPropertyApi(): void
    {
        self::assertInstanceOf(ListingPropertyApiInterface::class, $this->buildEtsy()->getListingPropertyApi());
    }

    public function testGetListingPropertyApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getListingPropertyApi(), $etsy->getListingPropertyApi());
    }

    public function testGetListingTranslationApi(): void
    {
        self::assertInstanceOf(ListingTranslationApiInterface::class, $this->buildEtsy()->getListingTranslationApi());
    }

    public function testGetListingTranslationApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getListingTranslationApi(), $etsy->getListingTranslationApi());
    }

    public function testGetListingVariationImageApi(): void
    {
        self::assertInstanceOf(ListingVariationImageApiInterface::class, $this->buildEtsy()->getListingVariationImageApi());
    }

    public function testGetListingVariationImageApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getListingVariationImageApi(), $etsy->getListingVariationImageApi());
    }

    public function testGetListingVideoApi(): void
    {
        self::assertInstanceOf(ListingVideoApiInterface::class, $this->buildEtsy()->getListingVideoApi());
    }

    public function testGetListingVideoApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getListingVideoApi(), $etsy->getListingVideoApi());
    }

    public function testGetPaymentApi(): void
    {
        self::assertInstanceOf(PaymentApiInterface::class, $this->buildEtsy()->getPaymentApi());
    }

    public function testGetPaymentApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getPaymentApi(), $etsy->getPaymentApi());
    }

    public function testGetPingApi(): void
    {
        self::assertInstanceOf(PingApiInterface::class, $this->buildEtsy()->getPingApi());
    }

    public function testGetPingApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getPingApi(), $etsy->getPingApi());
    }

    public function testGetReviewApi(): void
    {
        self::assertInstanceOf(ReviewApiInterface::class, $this->buildEtsy()->getReviewApi());
    }

    public function testGetReviewApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getReviewApi(), $etsy->getReviewApi());
    }

    public function testGetSellerTaxonomyApi(): void
    {
        self::assertInstanceOf(SellerTaxonomyApiInterface::class, $this->buildEtsy()->getSellerTaxonomyApi());
    }

    public function testGetSellerTaxonomyApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getSellerTaxonomyApi(), $etsy->getSellerTaxonomyApi());
    }

    public function testGetShippingProfileApi(): void
    {
        self::assertInstanceOf(ShippingProfileApiInterface::class, $this->buildEtsy()->getShippingProfileApi());
    }

    public function testGetShippingProfileApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShippingProfileApi(), $etsy->getShippingProfileApi());
    }

    public function testGetShopApi(): void
    {
        self::assertInstanceOf(ShopApiInterface::class, $this->buildEtsy()->getShopApi());
    }

    public function testGetShopApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopApi(), $etsy->getShopApi());
    }

    public function testGetShopHolidayPreferenceApi(): void
    {
        self::assertInstanceOf(ShopHolidayPreferenceApiInterface::class, $this->buildEtsy()->getShopHolidayPreferenceApi());
    }

    public function testGetShopHolidayPreferenceApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopHolidayPreferenceApi(), $etsy->getShopHolidayPreferenceApi());
    }

    public function testGetShopListingApi(): void
    {
        self::assertInstanceOf(ShopListingApiInterface::class, $this->buildEtsy()->getShopListingApi());
    }

    public function testGetShopListingApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopListingApi(), $etsy->getShopListingApi());
    }

    public function testGetShopProductionPartnerApi(): void
    {
        self::assertInstanceOf(ShopProductionPartnerApiInterface::class, $this->buildEtsy()->getShopProductionPartnerApi());
    }

    public function testGetShopProductionPartnerApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopProductionPartnerApi(), $etsy->getShopProductionPartnerApi());
    }

    public function testGetShopReadinessStateDefinitionApi(): void
    {
        self::assertInstanceOf(ShopReadinessStateDefinitionApiInterface::class, $this->buildEtsy()->getShopReadinessStateDefinitionApi());
    }

    public function testGetShopReadinessStateDefinitionApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopReadinessStateDefinitionApi(), $etsy->getShopReadinessStateDefinitionApi());
    }

    public function testGetShopReceiptApi(): void
    {
        self::assertInstanceOf(ShopReceiptApiInterface::class, $this->buildEtsy()->getShopReceiptApi());
    }

    public function testGetShopReceiptApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopReceiptApi(), $etsy->getShopReceiptApi());
    }

    public function testGetShopReceiptTransactionApi(): void
    {
        self::assertInstanceOf(ShopReceiptTransactionApiInterface::class, $this->buildEtsy()->getShopReceiptTransactionApi());
    }

    public function testGetShopReceiptTransactionApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopReceiptTransactionApi(), $etsy->getShopReceiptTransactionApi());
    }

    public function testGetShopReturnPolicyApi(): void
    {
        self::assertInstanceOf(ShopReturnPolicyApiInterface::class, $this->buildEtsy()->getShopReturnPolicyApi());
    }

    public function testGetShopReturnPolicyApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopReturnPolicyApi(), $etsy->getShopReturnPolicyApi());
    }

    public function testGetShopSectionApi(): void
    {
        self::assertInstanceOf(ShopSectionApiInterface::class, $this->buildEtsy()->getShopSectionApi());
    }

    public function testGetShopSectionApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopSectionApi(), $etsy->getShopSectionApi());
    }

    public function testGetUserAddressApi(): void
    {
        self::assertInstanceOf(UserAddressApiInterface::class, $this->buildEtsy()->getUserAddressApi());
    }

    public function testGetUserAddressApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getUserAddressApi(), $etsy->getUserAddressApi());
    }

    public function testGetUserApi(): void
    {
        self::assertInstanceOf(UserApiInterface::class, $this->buildEtsy()->getUserApi());
    }

    public function testGetUserApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getUserApi(), $etsy->getUserApi());
    }

    private function buildEtsy(): Etsy
    {
        return new Etsy(
            42,
            'test-keystring',
            'test-shared-secret',
            self::createStub(TtlAwareKeyValueStoreInterface::class),
            self::createStub(KeyValueStoreInterface::class),
        );
    }
}
