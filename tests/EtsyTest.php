<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests;

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
use ChristianBrown\Etsy\Etsy;
use ChristianBrown\Etsy\EtsyInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function array_key_exists;
use function explode;
use function str_replace;
use function ucwords;

#[CoversClass(Etsy::class)]
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

    private function buildEtsy(): EtsyInterface
    {
        $services = [];
        $container = self::createStub(ContainerInterface::class);
        $container->method('get')->willReturnCallback(
            function (string $id) use (&$services): object {
                if (!array_key_exists($id, $services)) {
                    $services[$id] = $this->createStub(self::interfaceFor($id));
                }

                return $services[$id];
            }
        );

        return new Etsy($container);
    }

    /**
     * @return class-string
     */
    private static function interfaceFor(string $serviceId): string
    {
        $parts = explode('.', $serviceId);
        $name = str_replace(' ', '', ucwords(str_replace('_', ' ', $parts[2]))).'Interface';

        /**
         * @var class-string $class
         */
        $class = 'ChristianBrown\Etsy\Api\\'.$name;

        return $class;
    }
}
