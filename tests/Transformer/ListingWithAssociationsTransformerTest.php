<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingBuyerPriceInterface;
use ChristianBrown\Etsy\Model\ListingImageInterface;
use ChristianBrown\Etsy\Model\ListingInventoryInterface;
use ChristianBrown\Etsy\Model\ListingPersonalizationInterface;
use ChristianBrown\Etsy\Model\ListingTranslationInterface;
use ChristianBrown\Etsy\Model\ListingVideoInterface;
use ChristianBrown\Etsy\Model\ListingWithAssociations;
use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;
use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Model\ShopInterface;
use ChristianBrown\Etsy\Model\ShopProductionPartnerInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;
use ChristianBrown\Etsy\Model\UserInterface;
use ChristianBrown\Etsy\Transformer\ListingBuyerPriceTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingImagesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingPersonalizationTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingTranslationTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingVideosTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsTransformerInterface;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnersTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopTransformerInterface;
use ChristianBrown\Etsy\Transformer\UserTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function array_keys;
use function sprintf;

#[CoversClass(ListingWithAssociations::class)]
#[CoversClass(ListingWithAssociationsTransformer::class)]
final class ListingWithAssociationsTransformerTest extends TestCase
{
    public function testSetListingId(): void
    {
        $listing = new ListingWithAssociations(1);

        self::assertSame(2, $listing->setListingId(2)->getListingId());
    }

    public function testTransform(): void
    {
        $priceData = ['__price__'];
        $price = self::createStub(MoneyInterface::class);
        $convertedPriceData = ['__convertedPrice__'];
        $convertedPrice = self::createStub(MoneyInterface::class);
        $buyerPriceData = ['__buyer_price__'];
        $buyerPrice = self::createStub(ListingBuyerPriceInterface::class);
        $imagesData = [['__images__']];
        $imagesItem = self::createStub(ListingImageInterface::class);
        $images = [$imagesItem];
        $inventoryData = ['__inventory__'];
        $inventory = self::createStub(ListingInventoryInterface::class);
        $personalizationData = ['__personalization__'];
        $personalization = self::createStub(ListingPersonalizationInterface::class);
        $productionPartnersData = [['__production_partners__']];
        $productionPartnersItem = self::createStub(ShopProductionPartnerInterface::class);
        $productionPartners = [$productionPartnersItem];
        $shippingProfileData = ['__shipping_profile__'];
        $shippingProfile = self::createStub(ShopShippingProfileInterface::class);
        $shopData = ['__shop__'];
        $shop = self::createStub(ShopInterface::class);
        $translationsData = ['__translations__'];
        $translations = self::createStub(ListingTranslationInterface::class);
        $userData = ['__user__'];
        $user = self::createStub(UserInterface::class);
        $videosData = [['__videos__']];
        $videosItem = self::createStub(ListingVideoInterface::class);
        $videos = [$videosItem];

        $data = [
            ListingWithAssociationsTransformerInterface::KEY_BUYER_PRICE => $buyerPriceData,
            ListingWithAssociationsTransformerInterface::KEY_CONVERTED_PRICE => $convertedPriceData,
            ListingWithAssociationsTransformerInterface::KEY_CREATED_TIMESTAMP => 1001,
            ListingWithAssociationsTransformerInterface::KEY_CREATION_TIMESTAMP => 1002,
            ListingWithAssociationsTransformerInterface::KEY_DESCRIPTION => 'v_description',
            ListingWithAssociationsTransformerInterface::KEY_ENDING_TIMESTAMP => 1003,
            ListingWithAssociationsTransformerInterface::KEY_FEATURED_RANK => 1004,
            ListingWithAssociationsTransformerInterface::KEY_FILE_DATA => 'v_fileData',
            ListingWithAssociationsTransformerInterface::KEY_HAS_VARIATIONS => true,
            ListingWithAssociationsTransformerInterface::KEY_IMAGES => $imagesData,
            ListingWithAssociationsTransformerInterface::KEY_INVENTORY => $inventoryData,
            ListingWithAssociationsTransformerInterface::KEY_IS_CUSTOMIZABLE => true,
            ListingWithAssociationsTransformerInterface::KEY_IS_PERSONALIZABLE => true,
            ListingWithAssociationsTransformerInterface::KEY_IS_PRIVATE => true,
            ListingWithAssociationsTransformerInterface::KEY_IS_SUPPLY => true,
            ListingWithAssociationsTransformerInterface::KEY_IS_TAXABLE => true,
            ListingWithAssociationsTransformerInterface::KEY_ITEM_DIMENSIONS_UNIT => 'v_itemDimensionsUnit',
            ListingWithAssociationsTransformerInterface::KEY_ITEM_HEIGHT => 1005.5,
            ListingWithAssociationsTransformerInterface::KEY_ITEM_LENGTH => 1006.5,
            ListingWithAssociationsTransformerInterface::KEY_ITEM_WEIGHT => 1007.5,
            ListingWithAssociationsTransformerInterface::KEY_ITEM_WEIGHT_UNIT => 'v_itemWeightUnit',
            ListingWithAssociationsTransformerInterface::KEY_ITEM_WIDTH => 1008.5,
            ListingWithAssociationsTransformerInterface::KEY_LANGUAGE => 'v_language',
            ListingWithAssociationsTransformerInterface::KEY_LAST_MODIFIED_TIMESTAMP => 1009,
            ListingWithAssociationsTransformerInterface::KEY_LISTING_TYPE => 'v_listingType',
            ListingWithAssociationsTransformerInterface::KEY_MATERIALS => ['a_materials', 'b_materials'],
            ListingWithAssociationsTransformerInterface::KEY_NON_TAXABLE => true,
            ListingWithAssociationsTransformerInterface::KEY_NUM_FAVORERS => 1010,
            ListingWithAssociationsTransformerInterface::KEY_ORIGINAL_CREATION_TIMESTAMP => 1011,
            ListingWithAssociationsTransformerInterface::KEY_PERSONALIZATION => $personalizationData,
            ListingWithAssociationsTransformerInterface::KEY_PRICE => $priceData,
            ListingWithAssociationsTransformerInterface::KEY_PROCESSING_MAX => 1012,
            ListingWithAssociationsTransformerInterface::KEY_PROCESSING_MIN => 1013,
            ListingWithAssociationsTransformerInterface::KEY_PRODUCTION_PARTNERS => $productionPartnersData,
            ListingWithAssociationsTransformerInterface::KEY_QUANTITY => 1014,
            ListingWithAssociationsTransformerInterface::KEY_READINESS_STATE_ID => 1015,
            ListingWithAssociationsTransformerInterface::KEY_RETURN_POLICY_ID => 1016,
            ListingWithAssociationsTransformerInterface::KEY_RICH_DESCRIPTION => 'v_richDescription',
            ListingWithAssociationsTransformerInterface::KEY_SHIPPING_PROFILE => $shippingProfileData,
            ListingWithAssociationsTransformerInterface::KEY_SHIPPING_PROFILE_ID => 1017,
            ListingWithAssociationsTransformerInterface::KEY_SHOP => $shopData,
            ListingWithAssociationsTransformerInterface::KEY_SHOP_ID => 1018,
            ListingWithAssociationsTransformerInterface::KEY_SHOP_SECTION_ID => 1019,
            ListingWithAssociationsTransformerInterface::KEY_SHOULD_AUTO_RENEW => true,
            ListingWithAssociationsTransformerInterface::KEY_SKUS => ['a_skus', 'b_skus'],
            ListingWithAssociationsTransformerInterface::KEY_STATE => 'v_state',
            ListingWithAssociationsTransformerInterface::KEY_STATE_TIMESTAMP => 1020,
            ListingWithAssociationsTransformerInterface::KEY_STYLE => ['a_style', 'b_style'],
            ListingWithAssociationsTransformerInterface::KEY_SUGGESTED_TITLE => 'v_suggestedTitle',
            ListingWithAssociationsTransformerInterface::KEY_TAGS => ['a_tags', 'b_tags'],
            ListingWithAssociationsTransformerInterface::KEY_TAXONOMY_ID => 1021,
            ListingWithAssociationsTransformerInterface::KEY_TITLE => 'v_title',
            ListingWithAssociationsTransformerInterface::KEY_TRANSLATIONS => ['de' => $translationsData],
            ListingWithAssociationsTransformerInterface::KEY_UPDATED_TIMESTAMP => 1022,
            ListingWithAssociationsTransformerInterface::KEY_URL => 'v_url',
            ListingWithAssociationsTransformerInterface::KEY_USER => $userData,
            ListingWithAssociationsTransformerInterface::KEY_USER_ID => 1023,
            ListingWithAssociationsTransformerInterface::KEY_VIDEOS => $videosData,
            ListingWithAssociationsTransformerInterface::KEY_VIEWS => 1024,
            ListingWithAssociationsTransformerInterface::KEY_WHEN_MADE => 'v_whenMade',
            ListingWithAssociationsTransformerInterface::KEY_WHO_MADE => 'v_whoMade',
            ListingWithAssociationsTransformerInterface::KEY_LISTING_ID => 9000,
        ];

        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);
        $moneyTransformer->method('transform')
            ->willReturnMap(
                [
                    [$priceData, $price],
                    [$convertedPriceData, $convertedPrice],
                ]
            );

        $listingBuyerPriceTransformer = self::createMock(ListingBuyerPriceTransformerInterface::class);
        $listingBuyerPriceTransformer->expects(self::once())->method('transform')
            ->with($buyerPriceData)
            ->willReturn($buyerPrice);

        $listingImagesTransformer = self::createMock(ListingImagesTransformerInterface::class);
        $listingImagesTransformer->expects(self::once())->method('transform')
            ->with($imagesData)
            ->willReturn($images);

        $listingInventoryTransformer = self::createMock(ListingInventoryTransformerInterface::class);
        $listingInventoryTransformer->expects(self::once())->method('transform')
            ->with($inventoryData)
            ->willReturn($inventory);

        $listingPersonalizationTransformer = self::createMock(ListingPersonalizationTransformerInterface::class);
        $listingPersonalizationTransformer->expects(self::once())->method('transform')
            ->with($personalizationData)
            ->willReturn($personalization);

        $shopProductionPartnersTransformer = self::createMock(ShopProductionPartnersTransformerInterface::class);
        $shopProductionPartnersTransformer->expects(self::once())->method('transform')
            ->with($productionPartnersData)
            ->willReturn($productionPartners);

        $shopShippingProfileTransformer = self::createMock(ShopShippingProfileTransformerInterface::class);
        $shopShippingProfileTransformer->expects(self::once())->method('transform')
            ->with($shippingProfileData)
            ->willReturn($shippingProfile);

        $shopTransformer = self::createMock(ShopTransformerInterface::class);
        $shopTransformer->expects(self::once())->method('transform')
            ->with($shopData)
            ->willReturn($shop);

        $listingTranslationTransformer = self::createMock(ListingTranslationTransformerInterface::class);
        $listingTranslationTransformer->expects(self::once())->method('transform')
            ->with($translationsData)
            ->willReturn($translations);

        $userTransformer = self::createMock(UserTransformerInterface::class);
        $userTransformer->expects(self::once())->method('transform')
            ->with($userData)
            ->willReturn($user);

        $listingVideosTransformer = self::createMock(ListingVideosTransformerInterface::class);
        $listingVideosTransformer->expects(self::once())->method('transform')
            ->with($videosData)
            ->willReturn($videos);

        $transformer = new ListingWithAssociationsTransformer(
            $listingBuyerPriceTransformer,
            $listingImagesTransformer,
            $listingInventoryTransformer,
            $listingPersonalizationTransformer,
            $listingTranslationTransformer,
            $listingVideosTransformer,
            $moneyTransformer,
            $shopProductionPartnersTransformer,
            $shopShippingProfileTransformer,
            $shopTransformer,
            $userTransformer,
        );

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getListingId());
        self::assertSame($buyerPrice, $actual->getBuyerPrice());
        self::assertSame($convertedPrice, $actual->getConvertedPrice());
        self::assertSame('v_description', $actual->getDescription());
        self::assertSame('v_fileData', $actual->getFileData());
        self::assertTrue($actual->getHasVariations());
        self::assertSame($images, $actual->getImages());
        self::assertSame($inventory, $actual->getInventory());
        self::assertTrue($actual->getIsCustomizable());
        self::assertTrue($actual->getIsPersonalizable());
        self::assertTrue($actual->getIsPrivate());
        self::assertTrue($actual->getIsSupply());
        self::assertTrue($actual->getIsTaxable());
        self::assertSame('v_itemDimensionsUnit', $actual->getItemDimensionsUnit());
        self::assertSame('v_itemWeightUnit', $actual->getItemWeightUnit());
        self::assertSame('v_language', $actual->getLanguage());
        self::assertSame('v_listingType', $actual->getListingType());
        self::assertSame(['a_materials', 'b_materials'], $actual->getMaterials());
        self::assertTrue($actual->getNonTaxable());
        self::assertSame($personalization, $actual->getPersonalization());
        self::assertSame($price, $actual->getPrice());
        self::assertSame($productionPartners, $actual->getProductionPartners());
        self::assertSame('v_richDescription', $actual->getRichDescription());
        self::assertSame($shippingProfile, $actual->getShippingProfile());
        self::assertSame($shop, $actual->getShop());
        self::assertTrue($actual->getShouldAutoRenew());
        self::assertSame(['a_skus', 'b_skus'], $actual->getSkus());
        self::assertSame('v_state', $actual->getState());
        self::assertSame(['a_style', 'b_style'], $actual->getStyle());
        self::assertSame('v_suggestedTitle', $actual->getSuggestedTitle());
        self::assertSame(['a_tags', 'b_tags'], $actual->getTags());
        self::assertSame('v_title', $actual->getTitle());
        self::assertSame(['de' => $translations], $actual->getTranslations());
        self::assertSame('v_url', $actual->getUrl());
        self::assertSame($user, $actual->getUser());
        self::assertSame($videos, $actual->getVideos());
        self::assertSame('v_whenMade', $actual->getWhenMade());
        self::assertSame('v_whoMade', $actual->getWhoMade());

        // scalar numeric assertions
        self::assertSame(1001, $actual->getCreatedTimestamp());
        self::assertSame(1002, $actual->getCreationTimestamp());
        self::assertSame(1003, $actual->getEndingTimestamp());
        self::assertSame(1004, $actual->getFeaturedRank());
        self::assertSame(1005.5, $actual->getItemHeight());
        self::assertSame(1006.5, $actual->getItemLength());
        self::assertSame(1007.5, $actual->getItemWeight());
        self::assertSame(1008.5, $actual->getItemWidth());
        self::assertSame(1009, $actual->getLastModifiedTimestamp());
        self::assertSame(1010, $actual->getNumFavorers());
        self::assertSame(1011, $actual->getOriginalCreationTimestamp());
        self::assertSame(1012, $actual->getProcessingMax());
        self::assertSame(1013, $actual->getProcessingMin());
        self::assertSame(1014, $actual->getQuantity());
        self::assertSame(1015, $actual->getReadinessStateId());
        self::assertSame(1016, $actual->getReturnPolicyId());
        self::assertSame(1017, $actual->getShippingProfileId());
        self::assertSame(1018, $actual->getShopId());
        self::assertSame(1019, $actual->getShopSectionId());
        self::assertSame(1020, $actual->getStateTimestamp());
        self::assertSame(1021, $actual->getTaxonomyId());
        self::assertSame(1022, $actual->getUpdatedTimestamp());
        self::assertSame(1023, $actual->getUserId());
        self::assertSame(1024, $actual->getViews());
    }

    /**
     * @param array<string, mixed>                            $data
     * @param Closure(ListingWithAssociationsInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ListingWithAssociationsTransformer(
            self::createStub(ListingBuyerPriceTransformerInterface::class),
            self::createStub(ListingImagesTransformerInterface::class),
            self::createStub(ListingInventoryTransformerInterface::class),
            self::createStub(ListingPersonalizationTransformerInterface::class),
            self::createStub(ListingTranslationTransformerInterface::class),
            self::createStub(ListingVideosTransformerInterface::class),
            self::createStub(MoneyTransformerInterface::class),
            self::createStub(ShopProductionPartnersTransformerInterface::class),
            self::createStub(ShopShippingProfileTransformerInterface::class),
            self::createStub(ShopTransformerInterface::class),
            self::createStub(UserTransformerInterface::class),
        );

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ListingWithAssociationsInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ListingWithAssociationsTransformerInterface::KEY_LISTING_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ListingWithAssociationsInterface $m): void {
                self::assertSame(1, $m->getListingId());
                self::assertNull($m->getBuyerPrice());
                self::assertNull($m->getConvertedPrice());
                self::assertNull($m->getCreatedTimestamp());
                self::assertNull($m->getCreationTimestamp());
                self::assertNull($m->getDescription());
                self::assertNull($m->getEndingTimestamp());
                self::assertNull($m->getFeaturedRank());
                self::assertNull($m->getFileData());
                self::assertNull($m->getHasVariations());
                self::assertSame([], $m->getImages());
                self::assertNull($m->getInventory());
                self::assertNull($m->getIsCustomizable());
                self::assertNull($m->getIsPersonalizable());
                self::assertNull($m->getIsPrivate());
                self::assertNull($m->getIsSupply());
                self::assertNull($m->getIsTaxable());
                self::assertNull($m->getItemDimensionsUnit());
                self::assertNull($m->getItemHeight());
                self::assertNull($m->getItemLength());
                self::assertNull($m->getItemWeight());
                self::assertNull($m->getItemWeightUnit());
                self::assertNull($m->getItemWidth());
                self::assertNull($m->getLanguage());
                self::assertNull($m->getLastModifiedTimestamp());
                self::assertNull($m->getListingType());
                self::assertSame([], $m->getMaterials());
                self::assertNull($m->getNonTaxable());
                self::assertNull($m->getNumFavorers());
                self::assertNull($m->getOriginalCreationTimestamp());
                self::assertNull($m->getPersonalization());
                self::assertNull($m->getPrice());
                self::assertNull($m->getProcessingMax());
                self::assertNull($m->getProcessingMin());
                self::assertSame([], $m->getProductionPartners());
                self::assertNull($m->getQuantity());
                self::assertNull($m->getReadinessStateId());
                self::assertNull($m->getReturnPolicyId());
                self::assertNull($m->getRichDescription());
                self::assertNull($m->getShippingProfile());
                self::assertNull($m->getShippingProfileId());
                self::assertNull($m->getShop());
                self::assertNull($m->getShopId());
                self::assertNull($m->getShopSectionId());
                self::assertNull($m->getShouldAutoRenew());
                self::assertSame([], $m->getSkus());
                self::assertNull($m->getState());
                self::assertNull($m->getStateTimestamp());
                self::assertSame([], $m->getStyle());
                self::assertNull($m->getSuggestedTitle());
                self::assertSame([], $m->getTags());
                self::assertNull($m->getTaxonomyId());
                self::assertNull($m->getTitle());
                self::assertSame([], $m->getTranslations());
                self::assertNull($m->getUpdatedTimestamp());
                self::assertNull($m->getUrl());
                self::assertNull($m->getUser());
                self::assertNull($m->getUserId());
                self::assertSame([], $m->getVideos());
                self::assertNull($m->getViews());
                self::assertNull($m->getWhenMade());
                self::assertNull($m->getWhoMade());
            },
        ];

        yield 'buyerPriceWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_BUYER_PRICE => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getBuyerPrice());
        }];
        yield 'convertedPriceWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_CONVERTED_PRICE => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getConvertedPrice());
        }];
        yield 'createdTimestampWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getCreatedTimestamp());
        }];
        yield 'creationTimestampWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_CREATION_TIMESTAMP => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getCreationTimestamp());
        }];
        yield 'descriptionWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_DESCRIPTION => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getDescription());
        }];
        yield 'endingTimestampWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ENDING_TIMESTAMP => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getEndingTimestamp());
        }];
        yield 'featuredRankWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_FEATURED_RANK => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getFeaturedRank());
        }];
        yield 'fileDataWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_FILE_DATA => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getFileData());
        }];
        yield 'hasVariationsWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_HAS_VARIATIONS => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getHasVariations());
        }];
        yield 'imagesWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_IMAGES => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getImages());
        }];
        yield 'inventoryWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_INVENTORY => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getInventory());
        }];
        yield 'isCustomizableWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_IS_CUSTOMIZABLE => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getIsCustomizable());
        }];
        yield 'isPersonalizableWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_IS_PERSONALIZABLE => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getIsPersonalizable());
        }];
        yield 'isPrivateWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_IS_PRIVATE => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getIsPrivate());
        }];
        yield 'isSupplyWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_IS_SUPPLY => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getIsSupply());
        }];
        yield 'isTaxableWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_IS_TAXABLE => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getIsTaxable());
        }];
        yield 'itemDimensionsUnitWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ITEM_DIMENSIONS_UNIT => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getItemDimensionsUnit());
        }];
        yield 'itemHeightWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ITEM_HEIGHT => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getItemHeight());
        }];
        yield 'itemHeightInt' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ITEM_HEIGHT => 7], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(7.0, $m->getItemHeight());
        }];
        yield 'itemLengthWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ITEM_LENGTH => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getItemLength());
        }];
        yield 'itemLengthInt' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ITEM_LENGTH => 7], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(7.0, $m->getItemLength());
        }];
        yield 'itemWeightWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ITEM_WEIGHT => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getItemWeight());
        }];
        yield 'itemWeightInt' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ITEM_WEIGHT => 7], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(7.0, $m->getItemWeight());
        }];
        yield 'itemWeightUnitWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ITEM_WEIGHT_UNIT => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getItemWeightUnit());
        }];
        yield 'itemWidthWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ITEM_WIDTH => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getItemWidth());
        }];
        yield 'itemWidthInt' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ITEM_WIDTH => 7], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(7.0, $m->getItemWidth());
        }];
        yield 'languageWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_LANGUAGE => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getLanguage());
        }];
        yield 'lastModifiedTimestampWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_LAST_MODIFIED_TIMESTAMP => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getLastModifiedTimestamp());
        }];
        yield 'listingTypeWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_LISTING_TYPE => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getListingType());
        }];
        yield 'materialsNonArray' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_MATERIALS => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getMaterials());
        }];
        yield 'materialsNonStringElement' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_MATERIALS => ['ok', 42]], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(['ok'], $m->getMaterials());
        }];
        yield 'materialsSingleString' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_MATERIALS => ['ok']], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(['ok'], $m->getMaterials());
        }];
        yield 'materialsSingleNonString' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_MATERIALS => [42]], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getMaterials());
        }];
        yield 'materialsEmpty' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_MATERIALS => []], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getMaterials());
        }];
        yield 'nonTaxableWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_NON_TAXABLE => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getNonTaxable());
        }];
        yield 'numFavorersWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_NUM_FAVORERS => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getNumFavorers());
        }];
        yield 'originalCreationTimestampWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_ORIGINAL_CREATION_TIMESTAMP => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getOriginalCreationTimestamp());
        }];
        yield 'personalizationWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_PERSONALIZATION => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getPersonalization());
        }];
        yield 'priceWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_PRICE => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getPrice());
        }];
        yield 'processingMaxWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_PROCESSING_MAX => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getProcessingMax());
        }];
        yield 'processingMinWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_PROCESSING_MIN => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getProcessingMin());
        }];
        yield 'productionPartnersWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_PRODUCTION_PARTNERS => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getProductionPartners());
        }];
        yield 'quantityWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_QUANTITY => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getQuantity());
        }];
        yield 'readinessStateIdWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_READINESS_STATE_ID => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getReadinessStateId());
        }];
        yield 'returnPolicyIdWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_RETURN_POLICY_ID => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getReturnPolicyId());
        }];
        yield 'richDescriptionWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_RICH_DESCRIPTION => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getRichDescription());
        }];
        yield 'shippingProfileWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SHIPPING_PROFILE => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getShippingProfile());
        }];
        yield 'shippingProfileIdWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SHIPPING_PROFILE_ID => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getShippingProfileId());
        }];
        yield 'shopWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SHOP => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getShop());
        }];
        yield 'shopIdWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SHOP_ID => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getShopId());
        }];
        yield 'shopSectionIdWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SHOP_SECTION_ID => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getShopSectionId());
        }];
        yield 'shouldAutoRenewWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SHOULD_AUTO_RENEW => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getShouldAutoRenew());
        }];
        yield 'skusNonArray' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SKUS => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getSkus());
        }];
        yield 'skusNonStringElement' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SKUS => ['ok', 42]], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(['ok'], $m->getSkus());
        }];
        yield 'skusSingleString' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SKUS => ['ok']], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(['ok'], $m->getSkus());
        }];
        yield 'skusSingleNonString' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SKUS => [42]], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getSkus());
        }];
        yield 'skusEmpty' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SKUS => []], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getSkus());
        }];
        yield 'stateWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_STATE => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getState());
        }];
        yield 'stateTimestampWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_STATE_TIMESTAMP => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getStateTimestamp());
        }];
        yield 'styleNonArray' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_STYLE => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getStyle());
        }];
        yield 'styleNonStringElement' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_STYLE => ['ok', 42]], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(['ok'], $m->getStyle());
        }];
        yield 'styleSingleString' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_STYLE => ['ok']], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(['ok'], $m->getStyle());
        }];
        yield 'styleSingleNonString' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_STYLE => [42]], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getStyle());
        }];
        yield 'styleEmpty' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_STYLE => []], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getStyle());
        }];
        yield 'suggestedTitleWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_SUGGESTED_TITLE => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getSuggestedTitle());
        }];
        yield 'tagsNonArray' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TAGS => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getTags());
        }];
        yield 'tagsNonStringElement' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TAGS => ['ok', 42]], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(['ok'], $m->getTags());
        }];
        yield 'tagsSingleString' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TAGS => ['ok']], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(['ok'], $m->getTags());
        }];
        yield 'tagsSingleNonString' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TAGS => [42]], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getTags());
        }];
        yield 'tagsEmpty' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TAGS => []], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getTags());
        }];
        yield 'taxonomyIdWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TAXONOMY_ID => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getTaxonomyId());
        }];
        yield 'titleWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TITLE => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getTitle());
        }];
        yield 'translationsWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TRANSLATIONS => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getTranslations());
        }];
        yield 'translationsEntryNonArray' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TRANSLATIONS => ['de' => 'x']], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getTranslations());
        }];
        yield 'translationsProcessThenSkip' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TRANSLATIONS => ['de' => ['__t__'], 'fr' => 'x']], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(['de'], array_keys($m->getTranslations()));
        }];
        yield 'translationsSkipThenProcess' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TRANSLATIONS => ['de' => 'x', 'fr' => ['__t__']]], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame(['fr'], array_keys($m->getTranslations()));
        }];
        yield 'translationsEmpty' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_TRANSLATIONS => []], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getTranslations());
        }];
        yield 'updatedTimestampWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_UPDATED_TIMESTAMP => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getUpdatedTimestamp());
        }];
        yield 'urlWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_URL => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getUrl());
        }];
        yield 'userWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_USER => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getUser());
        }];
        yield 'userIdWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_USER_ID => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getUserId());
        }];
        yield 'videosWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_VIDEOS => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertSame([], $m->getVideos());
        }];
        yield 'viewsWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_VIEWS => 'x'], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getViews());
        }];
        yield 'whenMadeWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_WHEN_MADE => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getWhenMade());
        }];
        yield 'whoMadeWrongType' => [[$id => 1, ListingWithAssociationsTransformerInterface::KEY_WHO_MADE => 42], static function (ListingWithAssociationsInterface $m): void {
            self::assertNull($m->getWhoMade());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ListingWithAssociationsTransformerInterface::KEY_LISTING_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidListingId(array $data): void
    {
        $transformer = new ListingWithAssociationsTransformer(
            self::createStub(ListingBuyerPriceTransformerInterface::class),
            self::createStub(ListingImagesTransformerInterface::class),
            self::createStub(ListingInventoryTransformerInterface::class),
            self::createStub(ListingPersonalizationTransformerInterface::class),
            self::createStub(ListingTranslationTransformerInterface::class),
            self::createStub(ListingVideosTransformerInterface::class),
            self::createStub(MoneyTransformerInterface::class),
            self::createStub(ShopProductionPartnersTransformerInterface::class),
            self::createStub(ShopShippingProfileTransformerInterface::class),
            self::createStub(ShopTransformerInterface::class),
            self::createStub(UserTransformerInterface::class),
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingWithAssociationsTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ListingWithAssociationsTransformerInterface::KEY_LISTING_ID));

        $transformer->transform($data);
    }
}
