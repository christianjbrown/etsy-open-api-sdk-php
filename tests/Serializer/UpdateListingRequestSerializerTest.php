<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateListingRequest;
use ChristianBrown\Etsy\Serializer\UpdateListingRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateListingRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateListingRequest::class)]
#[CoversClass(UpdateListingRequestSerializer::class)]
final class UpdateListingRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $description = 'test-description';
        $featuredRank = 1;
        $imageIds = [2, 3];
        $isSupply = true;
        $isTaxable = true;
        $itemDimensionsUnit = 'test-itemDimensionsUnit';
        $itemHeight = 4.5;
        $itemLength = 5.5;
        $itemWeight = 6.5;
        $itemWeightUnit = 'test-itemWeightUnit';
        $itemWidth = 7.5;
        $materials = ['test-materials-1', 'test-materials-2'];
        $productionPartnerIds = [9, 10];
        $returnPolicyId = 11;
        $shippingProfileId = 12;
        $shopSectionId = 13;
        $shouldAutoRenew = true;
        $state = 'test-state';
        $tags = ['test-tags-1', 'test-tags-2'];
        $taxonomyId = 14;
        $title = 'test-title';
        $type = 'test-type';
        $whenMade = 'test-whenMade';
        $whoMade = 'test-whoMade';
        $ecgtAfterSalesServiceInfo = 'test-ecgtAfterSalesServiceInfo';
        $ecgtGaranBrand = 'test-ecgtGaranBrand';
        $ecgtGaranGuaranteeDetails = 'test-ecgtGaranGuaranteeDetails';
        $ecgtGaranModel = 'test-ecgtGaranModel';
        $ecgtGaranYears = 15;
        $ecgtOtherCommercialGuaranteeDetails = 'test-ecgtOtherCommercialGuaranteeDetails';
        $ecgtSoftwareUpdateDetails = 'test-ecgtSoftwareUpdateDetails';

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [11, 'test-int-11'],
                    [12, 'test-int-12'],
                    [13, 'test-int-13'],
                    [14, 'test-int-14'],
                    [15, 'test-int-15'],
                ]
            );
        $formValueEncoder->method('encodeFloat')
            ->willReturnMap(
                [
                    [4.5, 'test-float-4.5'],
                    [5.5, 'test-float-5.5'],
                    [6.5, 'test-float-6.5'],
                    [7.5, 'test-float-7.5'],
                ]
            );
        $formValueEncoder->method('encodeBool')
            ->willReturnMap(
                [
                    [true, 'test-bool-true'],
                    [false, 'test-bool-false'],
                ]
            );
        $formValueEncoder->method('encodeStringList')
            ->willReturnMap(
                [
                    [UpdateListingRequestSerializerInterface::KEY_MATERIALS, $materials, ['test-materials' => 'test-materials-value']],
                    [UpdateListingRequestSerializerInterface::KEY_TAGS, $tags, ['test-tags' => 'test-tags-value']],
                ]
            );
        $formValueEncoder->method('encodeIntList')
            ->willReturnMap(
                [
                    [UpdateListingRequestSerializerInterface::KEY_IMAGE_IDS, $imageIds, ['test-imageIds' => 'test-imageIds-value']],
                    [UpdateListingRequestSerializerInterface::KEY_PRODUCTION_PARTNER_IDS, $productionPartnerIds, ['test-productionPartnerIds' => 'test-productionPartnerIds-value']],
                ]
            );

        $updateListingRequest = (new UpdateListingRequest())
            ->setDescription($description)
            ->setEcgtAfterSalesServiceInfo($ecgtAfterSalesServiceInfo)
            ->setEcgtGaranBrand($ecgtGaranBrand)
            ->setEcgtGaranGuaranteeDetails($ecgtGaranGuaranteeDetails)
            ->setEcgtGaranModel($ecgtGaranModel)
            ->setEcgtGaranYears($ecgtGaranYears)
            ->setEcgtOtherCommercialGuaranteeDetails($ecgtOtherCommercialGuaranteeDetails)
            ->setEcgtSoftwareUpdateDetails($ecgtSoftwareUpdateDetails)
            ->setFeaturedRank($featuredRank)
            ->setImageIds($imageIds)
            ->setIsSupply($isSupply)
            ->setIsTaxable($isTaxable)
            ->setItemDimensionsUnit($itemDimensionsUnit)
            ->setItemHeight($itemHeight)
            ->setItemLength($itemLength)
            ->setItemWeight($itemWeight)
            ->setItemWeightUnit($itemWeightUnit)
            ->setItemWidth($itemWidth)
            ->setMaterials($materials)
            ->setProductionPartnerIds($productionPartnerIds)
            ->setReturnPolicyId($returnPolicyId)
            ->setShippingProfileId($shippingProfileId)
            ->setShopSectionId($shopSectionId)
            ->setShouldAutoRenew($shouldAutoRenew)
            ->setState($state)
            ->setTags($tags)
            ->setTaxonomyId($taxonomyId)
            ->setTitle($title)
            ->setType($type)
            ->setWhenMade($whenMade)
            ->setWhoMade($whoMade);

        $serializer = new UpdateListingRequestSerializer($formValueEncoder);

        $expected = [
            UpdateListingRequestSerializerInterface::KEY_DESCRIPTION => $description,
            UpdateListingRequestSerializerInterface::KEY_ECGT_AFTER_SALES_SERVICE_INFO => $ecgtAfterSalesServiceInfo,
            UpdateListingRequestSerializerInterface::KEY_ECGT_GARAN_BRAND => $ecgtGaranBrand,
            UpdateListingRequestSerializerInterface::KEY_ECGT_GARAN_GUARANTEE_DETAILS => $ecgtGaranGuaranteeDetails,
            UpdateListingRequestSerializerInterface::KEY_ECGT_GARAN_MODEL => $ecgtGaranModel,
            UpdateListingRequestSerializerInterface::KEY_ECGT_GARAN_YEARS => 'test-int-15',
            UpdateListingRequestSerializerInterface::KEY_ECGT_OTHER_COMMERCIAL_GUARANTEE_DETAILS => $ecgtOtherCommercialGuaranteeDetails,
            UpdateListingRequestSerializerInterface::KEY_ECGT_SOFTWARE_UPDATE_DETAILS => $ecgtSoftwareUpdateDetails,
            UpdateListingRequestSerializerInterface::KEY_FEATURED_RANK => 'test-int-1',
            'test-imageIds' => 'test-imageIds-value',
            UpdateListingRequestSerializerInterface::KEY_IS_SUPPLY => 'test-bool-true',
            UpdateListingRequestSerializerInterface::KEY_IS_TAXABLE => 'test-bool-true',
            UpdateListingRequestSerializerInterface::KEY_ITEM_DIMENSIONS_UNIT => $itemDimensionsUnit,
            UpdateListingRequestSerializerInterface::KEY_ITEM_HEIGHT => 'test-float-4.5',
            UpdateListingRequestSerializerInterface::KEY_ITEM_LENGTH => 'test-float-5.5',
            UpdateListingRequestSerializerInterface::KEY_ITEM_WEIGHT => 'test-float-6.5',
            UpdateListingRequestSerializerInterface::KEY_ITEM_WEIGHT_UNIT => $itemWeightUnit,
            UpdateListingRequestSerializerInterface::KEY_ITEM_WIDTH => 'test-float-7.5',
            'test-materials' => 'test-materials-value',
            'test-productionPartnerIds' => 'test-productionPartnerIds-value',
            UpdateListingRequestSerializerInterface::KEY_RETURN_POLICY_ID => 'test-int-11',
            UpdateListingRequestSerializerInterface::KEY_SHIPPING_PROFILE_ID => 'test-int-12',
            UpdateListingRequestSerializerInterface::KEY_SHOP_SECTION_ID => 'test-int-13',
            UpdateListingRequestSerializerInterface::KEY_SHOULD_AUTO_RENEW => 'test-bool-true',
            UpdateListingRequestSerializerInterface::KEY_STATE => $state,
            'test-tags' => 'test-tags-value',
            UpdateListingRequestSerializerInterface::KEY_TAXONOMY_ID => 'test-int-14',
            UpdateListingRequestSerializerInterface::KEY_TITLE => $title,
            UpdateListingRequestSerializerInterface::KEY_TYPE => $type,
            UpdateListingRequestSerializerInterface::KEY_WHEN_MADE => $whenMade,
            UpdateListingRequestSerializerInterface::KEY_WHO_MADE => $whoMade,
        ];

        self::assertSame($expected, $serializer->serialize($updateListingRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);

        $updateListingRequest = new UpdateListingRequest();

        $serializer = new UpdateListingRequestSerializer($formValueEncoder);

        self::assertSame([], $serializer->serialize($updateListingRequest));
    }
}
