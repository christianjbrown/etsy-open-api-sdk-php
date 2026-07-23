<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Listing;
use ChristianBrown\Etsy\Model\ListingInterface;
use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Transformer\ListingTransformer;
use ChristianBrown\Etsy\Transformer\ListingTransformerInterface;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(Listing::class)]
#[CoversClass(ListingTransformer::class)]
final class ListingTransformerTest extends TestCase
{
    public function testSetListingId(): void
    {
        $listing = new Listing(1);

        self::assertSame(2, $listing->setListingId(2)->getListingId());
    }

    public function testTransform(): void
    {
        $priceData = ['__price__'];
        $price = self::createStub(MoneyInterface::class);
        $convertedPriceData = ['__convertedPrice__'];
        $convertedPrice = self::createStub(MoneyInterface::class);

        $data = [
            ListingTransformerInterface::KEY_LISTING_ID => 9000,
            ListingTransformerInterface::KEY_USER_ID => 101,
            ListingTransformerInterface::KEY_SHOP_ID => 102,
            ListingTransformerInterface::KEY_TITLE => 'v_title',
            ListingTransformerInterface::KEY_DESCRIPTION => 'v_description',
            ListingTransformerInterface::KEY_RICH_DESCRIPTION => 'v_richDescription',
            ListingTransformerInterface::KEY_STATE => 'v_state',
            ListingTransformerInterface::KEY_CREATION_TIMESTAMP => 103,
            ListingTransformerInterface::KEY_CREATED_TIMESTAMP => 104,
            ListingTransformerInterface::KEY_ENDING_TIMESTAMP => 105,
            ListingTransformerInterface::KEY_ORIGINAL_CREATION_TIMESTAMP => 106,
            ListingTransformerInterface::KEY_LAST_MODIFIED_TIMESTAMP => 107,
            ListingTransformerInterface::KEY_UPDATED_TIMESTAMP => 108,
            ListingTransformerInterface::KEY_STATE_TIMESTAMP => 109,
            ListingTransformerInterface::KEY_QUANTITY => 110,
            ListingTransformerInterface::KEY_SHOP_SECTION_ID => 111,
            ListingTransformerInterface::KEY_FEATURED_RANK => 112,
            ListingTransformerInterface::KEY_URL => 'v_url',
            ListingTransformerInterface::KEY_NUM_FAVORERS => 113,
            ListingTransformerInterface::KEY_NON_TAXABLE => true,
            ListingTransformerInterface::KEY_IS_TAXABLE => true,
            ListingTransformerInterface::KEY_IS_CUSTOMIZABLE => true,
            ListingTransformerInterface::KEY_IS_PERSONALIZABLE => true,
            ListingTransformerInterface::KEY_LISTING_TYPE => 'v_listingType',
            ListingTransformerInterface::KEY_TAGS => ['a_tags', 'b_tags'],
            ListingTransformerInterface::KEY_MATERIALS => ['a_materials', 'b_materials'],
            ListingTransformerInterface::KEY_SHIPPING_PROFILE_ID => 114,
            ListingTransformerInterface::KEY_RETURN_POLICY_ID => 115,
            ListingTransformerInterface::KEY_PROCESSING_MIN => 116,
            ListingTransformerInterface::KEY_PROCESSING_MAX => 117,
            ListingTransformerInterface::KEY_WHO_MADE => 'v_whoMade',
            ListingTransformerInterface::KEY_WHEN_MADE => 'v_whenMade',
            ListingTransformerInterface::KEY_IS_SUPPLY => true,
            ListingTransformerInterface::KEY_ITEM_WEIGHT => 118.5,
            ListingTransformerInterface::KEY_ITEM_WEIGHT_UNIT => 'v_itemWeightUnit',
            ListingTransformerInterface::KEY_ITEM_LENGTH => 119.5,
            ListingTransformerInterface::KEY_ITEM_WIDTH => 120.5,
            ListingTransformerInterface::KEY_ITEM_HEIGHT => 121.5,
            ListingTransformerInterface::KEY_ITEM_DIMENSIONS_UNIT => 'v_itemDimensionsUnit',
            ListingTransformerInterface::KEY_IS_PRIVATE => true,
            ListingTransformerInterface::KEY_STYLE => ['a_style', 'b_style'],
            ListingTransformerInterface::KEY_FILE_DATA => 'v_fileData',
            ListingTransformerInterface::KEY_HAS_VARIATIONS => true,
            ListingTransformerInterface::KEY_SHOULD_AUTO_RENEW => true,
            ListingTransformerInterface::KEY_LANGUAGE => 'v_language',
            ListingTransformerInterface::KEY_PRICE => $priceData,
            ListingTransformerInterface::KEY_CONVERTED_PRICE => $convertedPriceData,
            ListingTransformerInterface::KEY_TAXONOMY_ID => 122,
            ListingTransformerInterface::KEY_READINESS_STATE_ID => 123,
            ListingTransformerInterface::KEY_SUGGESTED_TITLE => 'v_suggestedTitle',
        ];

        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);
        $moneyTransformer->method('transform')
            ->willReturnMap(
                [
                    [$priceData, $price],
                    [$convertedPriceData, $convertedPrice],
                ]
            );

        $transformer = new ListingTransformer($moneyTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getListingId());
        self::assertSame(101, $actual->getUserId());
        self::assertSame(102, $actual->getShopId());
        self::assertSame('v_title', $actual->getTitle());
        self::assertSame('v_description', $actual->getDescription());
        self::assertSame('v_richDescription', $actual->getRichDescription());
        self::assertSame('v_state', $actual->getState());
        self::assertSame(103, $actual->getCreationTimestamp());
        self::assertSame(104, $actual->getCreatedTimestamp());
        self::assertSame(105, $actual->getEndingTimestamp());
        self::assertSame(106, $actual->getOriginalCreationTimestamp());
        self::assertSame(107, $actual->getLastModifiedTimestamp());
        self::assertSame(108, $actual->getUpdatedTimestamp());
        self::assertSame(109, $actual->getStateTimestamp());
        self::assertSame(110, $actual->getQuantity());
        self::assertSame(111, $actual->getShopSectionId());
        self::assertSame(112, $actual->getFeaturedRank());
        self::assertSame('v_url', $actual->getUrl());
        self::assertSame(113, $actual->getNumFavorers());
        self::assertTrue($actual->getNonTaxable());
        self::assertTrue($actual->getIsTaxable());
        self::assertTrue($actual->getIsCustomizable());
        self::assertTrue($actual->getIsPersonalizable());
        self::assertSame('v_listingType', $actual->getListingType());
        self::assertSame(['a_tags', 'b_tags'], $actual->getTags());
        self::assertSame(['a_materials', 'b_materials'], $actual->getMaterials());
        self::assertSame(114, $actual->getShippingProfileId());
        self::assertSame(115, $actual->getReturnPolicyId());
        self::assertSame(116, $actual->getProcessingMin());
        self::assertSame(117, $actual->getProcessingMax());
        self::assertSame('v_whoMade', $actual->getWhoMade());
        self::assertSame('v_whenMade', $actual->getWhenMade());
        self::assertTrue($actual->getIsSupply());
        self::assertSame(118.5, $actual->getItemWeight());
        self::assertSame('v_itemWeightUnit', $actual->getItemWeightUnit());
        self::assertSame(119.5, $actual->getItemLength());
        self::assertSame(120.5, $actual->getItemWidth());
        self::assertSame(121.5, $actual->getItemHeight());
        self::assertSame('v_itemDimensionsUnit', $actual->getItemDimensionsUnit());
        self::assertTrue($actual->getIsPrivate());
        self::assertSame(['a_style', 'b_style'], $actual->getStyle());
        self::assertSame('v_fileData', $actual->getFileData());
        self::assertTrue($actual->getHasVariations());
        self::assertTrue($actual->getShouldAutoRenew());
        self::assertSame('v_language', $actual->getLanguage());
        self::assertSame($price, $actual->getPrice());
        self::assertSame($convertedPrice, $actual->getConvertedPrice());
        self::assertSame(122, $actual->getTaxonomyId());
        self::assertSame(123, $actual->getReadinessStateId());
        self::assertSame('v_suggestedTitle', $actual->getSuggestedTitle());
    }

    /**
     * @param array<string, mixed>            $data
     * @param Closure(ListingInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);

        $transformer = new ListingTransformer($moneyTransformer);

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ListingInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ListingTransformerInterface::KEY_LISTING_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ListingInterface $m): void {
                self::assertNull($m->getUserId());
                self::assertNull($m->getShopId());
                self::assertNull($m->getTitle());
                self::assertNull($m->getDescription());
                self::assertNull($m->getRichDescription());
                self::assertNull($m->getState());
                self::assertNull($m->getCreationTimestamp());
                self::assertNull($m->getCreatedTimestamp());
                self::assertNull($m->getEndingTimestamp());
                self::assertNull($m->getOriginalCreationTimestamp());
                self::assertNull($m->getLastModifiedTimestamp());
                self::assertNull($m->getUpdatedTimestamp());
                self::assertNull($m->getStateTimestamp());
                self::assertNull($m->getQuantity());
                self::assertNull($m->getShopSectionId());
                self::assertNull($m->getFeaturedRank());
                self::assertNull($m->getUrl());
                self::assertNull($m->getNumFavorers());
                self::assertNull($m->getNonTaxable());
                self::assertNull($m->getIsTaxable());
                self::assertNull($m->getIsCustomizable());
                self::assertNull($m->getIsPersonalizable());
                self::assertNull($m->getListingType());
                self::assertSame([], $m->getTags());
                self::assertSame([], $m->getMaterials());
                self::assertNull($m->getShippingProfileId());
                self::assertNull($m->getReturnPolicyId());
                self::assertNull($m->getProcessingMin());
                self::assertNull($m->getProcessingMax());
                self::assertNull($m->getWhoMade());
                self::assertNull($m->getWhenMade());
                self::assertNull($m->getIsSupply());
                self::assertNull($m->getItemWeight());
                self::assertNull($m->getItemWeightUnit());
                self::assertNull($m->getItemLength());
                self::assertNull($m->getItemWidth());
                self::assertNull($m->getItemHeight());
                self::assertNull($m->getItemDimensionsUnit());
                self::assertNull($m->getIsPrivate());
                self::assertSame([], $m->getStyle());
                self::assertNull($m->getFileData());
                self::assertNull($m->getHasVariations());
                self::assertNull($m->getShouldAutoRenew());
                self::assertNull($m->getLanguage());
                self::assertNull($m->getPrice());
                self::assertNull($m->getConvertedPrice());
                self::assertNull($m->getTaxonomyId());
                self::assertNull($m->getReadinessStateId());
                self::assertNull($m->getSuggestedTitle());
            },
        ];

        yield 'userIdWrongType' => [[$id => 1, ListingTransformerInterface::KEY_USER_ID => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getUserId());
        }];
        yield 'shopIdWrongType' => [[$id => 1, ListingTransformerInterface::KEY_SHOP_ID => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getShopId());
        }];
        yield 'titleWrongType' => [[$id => 1, ListingTransformerInterface::KEY_TITLE => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getTitle());
        }];
        yield 'descriptionWrongType' => [[$id => 1, ListingTransformerInterface::KEY_DESCRIPTION => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getDescription());
        }];
        yield 'richDescriptionWrongType' => [[$id => 1, ListingTransformerInterface::KEY_RICH_DESCRIPTION => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getRichDescription());
        }];
        yield 'stateWrongType' => [[$id => 1, ListingTransformerInterface::KEY_STATE => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getState());
        }];
        yield 'creationTimestampWrongType' => [[$id => 1, ListingTransformerInterface::KEY_CREATION_TIMESTAMP => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getCreationTimestamp());
        }];
        yield 'createdTimestampWrongType' => [[$id => 1, ListingTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getCreatedTimestamp());
        }];
        yield 'endingTimestampWrongType' => [[$id => 1, ListingTransformerInterface::KEY_ENDING_TIMESTAMP => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getEndingTimestamp());
        }];
        yield 'originalCreationTimestampWrongType' => [[$id => 1, ListingTransformerInterface::KEY_ORIGINAL_CREATION_TIMESTAMP => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getOriginalCreationTimestamp());
        }];
        yield 'lastModifiedTimestampWrongType' => [[$id => 1, ListingTransformerInterface::KEY_LAST_MODIFIED_TIMESTAMP => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getLastModifiedTimestamp());
        }];
        yield 'updatedTimestampWrongType' => [[$id => 1, ListingTransformerInterface::KEY_UPDATED_TIMESTAMP => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getUpdatedTimestamp());
        }];
        yield 'stateTimestampWrongType' => [[$id => 1, ListingTransformerInterface::KEY_STATE_TIMESTAMP => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getStateTimestamp());
        }];
        yield 'quantityWrongType' => [[$id => 1, ListingTransformerInterface::KEY_QUANTITY => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getQuantity());
        }];
        yield 'shopSectionIdWrongType' => [[$id => 1, ListingTransformerInterface::KEY_SHOP_SECTION_ID => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getShopSectionId());
        }];
        yield 'featuredRankWrongType' => [[$id => 1, ListingTransformerInterface::KEY_FEATURED_RANK => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getFeaturedRank());
        }];
        yield 'urlWrongType' => [[$id => 1, ListingTransformerInterface::KEY_URL => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getUrl());
        }];
        yield 'numFavorersWrongType' => [[$id => 1, ListingTransformerInterface::KEY_NUM_FAVORERS => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getNumFavorers());
        }];
        yield 'nonTaxableWrongType' => [[$id => 1, ListingTransformerInterface::KEY_NON_TAXABLE => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getNonTaxable());
        }];
        yield 'isTaxableWrongType' => [[$id => 1, ListingTransformerInterface::KEY_IS_TAXABLE => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getIsTaxable());
        }];
        yield 'isCustomizableWrongType' => [[$id => 1, ListingTransformerInterface::KEY_IS_CUSTOMIZABLE => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getIsCustomizable());
        }];
        yield 'isPersonalizableWrongType' => [[$id => 1, ListingTransformerInterface::KEY_IS_PERSONALIZABLE => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getIsPersonalizable());
        }];
        yield 'listingTypeWrongType' => [[$id => 1, ListingTransformerInterface::KEY_LISTING_TYPE => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getListingType());
        }];
        yield 'tagsNonArray' => [[$id => 1, ListingTransformerInterface::KEY_TAGS => 'x'], static function (ListingInterface $m): void {
            self::assertSame([], $m->getTags());
        }];
        yield 'tagsNonStringElement' => [[$id => 1, ListingTransformerInterface::KEY_TAGS => ['ok', 42]], static function (ListingInterface $m): void {
            self::assertSame(['ok'], $m->getTags());
        }];
        yield 'tagsSingleString' => [[$id => 1, ListingTransformerInterface::KEY_TAGS => ['ok']], static function (ListingInterface $m): void {
            self::assertSame(['ok'], $m->getTags());
        }];
        yield 'tagsSingleNonString' => [[$id => 1, ListingTransformerInterface::KEY_TAGS => [42]], static function (ListingInterface $m): void {
            self::assertSame([], $m->getTags());
        }];
        yield 'tagsEmpty' => [[$id => 1, ListingTransformerInterface::KEY_TAGS => []], static function (ListingInterface $m): void {
            self::assertSame([], $m->getTags());
        }];
        yield 'materialsNonArray' => [[$id => 1, ListingTransformerInterface::KEY_MATERIALS => 'x'], static function (ListingInterface $m): void {
            self::assertSame([], $m->getMaterials());
        }];
        yield 'materialsNonStringElement' => [[$id => 1, ListingTransformerInterface::KEY_MATERIALS => ['ok', 42]], static function (ListingInterface $m): void {
            self::assertSame(['ok'], $m->getMaterials());
        }];
        yield 'materialsSingleString' => [[$id => 1, ListingTransformerInterface::KEY_MATERIALS => ['ok']], static function (ListingInterface $m): void {
            self::assertSame(['ok'], $m->getMaterials());
        }];
        yield 'materialsSingleNonString' => [[$id => 1, ListingTransformerInterface::KEY_MATERIALS => [42]], static function (ListingInterface $m): void {
            self::assertSame([], $m->getMaterials());
        }];
        yield 'materialsEmpty' => [[$id => 1, ListingTransformerInterface::KEY_MATERIALS => []], static function (ListingInterface $m): void {
            self::assertSame([], $m->getMaterials());
        }];
        yield 'shippingProfileIdWrongType' => [[$id => 1, ListingTransformerInterface::KEY_SHIPPING_PROFILE_ID => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getShippingProfileId());
        }];
        yield 'returnPolicyIdWrongType' => [[$id => 1, ListingTransformerInterface::KEY_RETURN_POLICY_ID => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getReturnPolicyId());
        }];
        yield 'processingMinWrongType' => [[$id => 1, ListingTransformerInterface::KEY_PROCESSING_MIN => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getProcessingMin());
        }];
        yield 'processingMaxWrongType' => [[$id => 1, ListingTransformerInterface::KEY_PROCESSING_MAX => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getProcessingMax());
        }];
        yield 'whoMadeWrongType' => [[$id => 1, ListingTransformerInterface::KEY_WHO_MADE => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getWhoMade());
        }];
        yield 'whenMadeWrongType' => [[$id => 1, ListingTransformerInterface::KEY_WHEN_MADE => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getWhenMade());
        }];
        yield 'isSupplyWrongType' => [[$id => 1, ListingTransformerInterface::KEY_IS_SUPPLY => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getIsSupply());
        }];
        yield 'itemWeightWrongType' => [[$id => 1, ListingTransformerInterface::KEY_ITEM_WEIGHT => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getItemWeight());
        }];
        yield 'itemWeightInt' => [[$id => 1, ListingTransformerInterface::KEY_ITEM_WEIGHT => 7], static function (ListingInterface $m): void {
            self::assertSame(7.0, $m->getItemWeight());
        }];
        yield 'itemWeightUnitWrongType' => [[$id => 1, ListingTransformerInterface::KEY_ITEM_WEIGHT_UNIT => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getItemWeightUnit());
        }];
        yield 'itemLengthWrongType' => [[$id => 1, ListingTransformerInterface::KEY_ITEM_LENGTH => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getItemLength());
        }];
        yield 'itemLengthInt' => [[$id => 1, ListingTransformerInterface::KEY_ITEM_LENGTH => 7], static function (ListingInterface $m): void {
            self::assertSame(7.0, $m->getItemLength());
        }];
        yield 'itemWidthWrongType' => [[$id => 1, ListingTransformerInterface::KEY_ITEM_WIDTH => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getItemWidth());
        }];
        yield 'itemWidthInt' => [[$id => 1, ListingTransformerInterface::KEY_ITEM_WIDTH => 7], static function (ListingInterface $m): void {
            self::assertSame(7.0, $m->getItemWidth());
        }];
        yield 'itemHeightWrongType' => [[$id => 1, ListingTransformerInterface::KEY_ITEM_HEIGHT => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getItemHeight());
        }];
        yield 'itemHeightInt' => [[$id => 1, ListingTransformerInterface::KEY_ITEM_HEIGHT => 7], static function (ListingInterface $m): void {
            self::assertSame(7.0, $m->getItemHeight());
        }];
        yield 'itemDimensionsUnitWrongType' => [[$id => 1, ListingTransformerInterface::KEY_ITEM_DIMENSIONS_UNIT => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getItemDimensionsUnit());
        }];
        yield 'isPrivateWrongType' => [[$id => 1, ListingTransformerInterface::KEY_IS_PRIVATE => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getIsPrivate());
        }];
        yield 'styleNonArray' => [[$id => 1, ListingTransformerInterface::KEY_STYLE => 'x'], static function (ListingInterface $m): void {
            self::assertSame([], $m->getStyle());
        }];
        yield 'styleNonStringElement' => [[$id => 1, ListingTransformerInterface::KEY_STYLE => ['ok', 42]], static function (ListingInterface $m): void {
            self::assertSame(['ok'], $m->getStyle());
        }];
        yield 'styleSingleString' => [[$id => 1, ListingTransformerInterface::KEY_STYLE => ['ok']], static function (ListingInterface $m): void {
            self::assertSame(['ok'], $m->getStyle());
        }];
        yield 'styleSingleNonString' => [[$id => 1, ListingTransformerInterface::KEY_STYLE => [42]], static function (ListingInterface $m): void {
            self::assertSame([], $m->getStyle());
        }];
        yield 'styleEmpty' => [[$id => 1, ListingTransformerInterface::KEY_STYLE => []], static function (ListingInterface $m): void {
            self::assertSame([], $m->getStyle());
        }];
        yield 'fileDataWrongType' => [[$id => 1, ListingTransformerInterface::KEY_FILE_DATA => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getFileData());
        }];
        yield 'hasVariationsWrongType' => [[$id => 1, ListingTransformerInterface::KEY_HAS_VARIATIONS => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getHasVariations());
        }];
        yield 'shouldAutoRenewWrongType' => [[$id => 1, ListingTransformerInterface::KEY_SHOULD_AUTO_RENEW => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getShouldAutoRenew());
        }];
        yield 'languageWrongType' => [[$id => 1, ListingTransformerInterface::KEY_LANGUAGE => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getLanguage());
        }];
        yield 'priceWrongType' => [[$id => 1, ListingTransformerInterface::KEY_PRICE => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getPrice());
        }];
        yield 'convertedPriceWrongType' => [[$id => 1, ListingTransformerInterface::KEY_CONVERTED_PRICE => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getConvertedPrice());
        }];
        yield 'taxonomyIdWrongType' => [[$id => 1, ListingTransformerInterface::KEY_TAXONOMY_ID => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getTaxonomyId());
        }];
        yield 'readinessStateIdWrongType' => [[$id => 1, ListingTransformerInterface::KEY_READINESS_STATE_ID => 'x'], static function (ListingInterface $m): void {
            self::assertNull($m->getReadinessStateId());
        }];
        yield 'suggestedTitleWrongType' => [[$id => 1, ListingTransformerInterface::KEY_SUGGESTED_TITLE => 42], static function (ListingInterface $m): void {
            self::assertNull($m->getSuggestedTitle());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ListingTransformerInterface::KEY_LISTING_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidListingId(array $data): void
    {
        $transformer = new ListingTransformer(self::createStub(MoneyTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ListingTransformerInterface::KEY_LISTING_ID));

        $transformer->transform($data);
    }

    public function testTransformTransformsMoney(): void
    {
        $priceData = ['__price__'];
        $price = self::createStub(MoneyInterface::class);

        $moneyTransformer = self::createMock(MoneyTransformerInterface::class);
        $moneyTransformer->expects(self::once())->method('transform')
            ->with($priceData)
            ->willReturn($price);

        $transformer = new ListingTransformer($moneyTransformer);

        $actual = $transformer->transform([
            ListingTransformerInterface::KEY_LISTING_ID => 1,
            ListingTransformerInterface::KEY_PRICE => $priceData,
        ]);

        self::assertSame($price, $actual->getPrice());
        self::assertNull($actual->getConvertedPrice());
    }
}
