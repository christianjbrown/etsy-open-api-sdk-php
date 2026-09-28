<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\CreateDraftListingRequest;
use ChristianBrown\Etsy\Serializer\CreateDraftListingRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateDraftListingRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CreateDraftListingRequest::class)]
#[CoversClass(CreateDraftListingRequestSerializer::class)]
final class CreateDraftListingRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $quantity = 1;
        $title = 'test-title';
        $description = 'test-description';
        $price = 2.5;
        $whoMade = 'test-whoMade';
        $whenMade = 'test-whenMade';
        $taxonomyId = 3;
        $imageIds = [4, 5];
        $isCustomizable = true;
        $isSupply = true;
        $isTaxable = true;
        $itemDimensionsUnit = 'test-itemDimensionsUnit';
        $itemHeight = 6.5;
        $itemLength = 7.5;
        $itemWeight = 8.5;
        $itemWeightUnit = 'test-itemWeightUnit';
        $itemWidth = 9.5;
        $materials = ['test-materials-1', 'test-materials-2'];
        $processingMax = 11;
        $processingMin = 12;
        $productionPartnerIds = [13, 14];
        $readinessStateId = 15;
        $returnPolicyId = 16;
        $shippingProfileId = 17;
        $shopSectionId = 18;
        $shouldAutoRenew = true;
        $styles = ['test-styles-1', 'test-styles-2'];
        $tags = ['test-tags-1', 'test-tags-2'];
        $type = 'test-type';
        $ecgtAfterSalesServiceInfo = 'test-ecgtAfterSalesServiceInfo';
        $ecgtGaranBrand = 'test-ecgtGaranBrand';
        $ecgtGaranGuaranteeDetails = 'test-ecgtGaranGuaranteeDetails';
        $ecgtGaranModel = 'test-ecgtGaranModel';
        $ecgtGaranYears = 19;
        $ecgtOtherCommercialGuaranteeDetails = 'test-ecgtOtherCommercialGuaranteeDetails';
        $ecgtSoftwareUpdateDetails = 'test-ecgtSoftwareUpdateDetails';

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [3, 'test-int-3'],
                    [11, 'test-int-11'],
                    [12, 'test-int-12'],
                    [15, 'test-int-15'],
                    [16, 'test-int-16'],
                    [17, 'test-int-17'],
                    [18, 'test-int-18'],
                    [19, 'test-int-19'],
                ]
            );
        $formValueEncoder->method('encodeFloat')
            ->willReturnMap(
                [
                    [2.5, 'test-float-2.5'],
                    [6.5, 'test-float-6.5'],
                    [7.5, 'test-float-7.5'],
                    [8.5, 'test-float-8.5'],
                    [9.5, 'test-float-9.5'],
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
                    [CreateDraftListingRequestSerializerInterface::KEY_MATERIALS, $materials, ['test-materials' => 'test-materials-value']],
                    [CreateDraftListingRequestSerializerInterface::KEY_STYLES, $styles, ['test-styles' => 'test-styles-value']],
                    [CreateDraftListingRequestSerializerInterface::KEY_TAGS, $tags, ['test-tags' => 'test-tags-value']],
                ]
            );
        $formValueEncoder->method('encodeIntList')
            ->willReturnMap(
                [
                    [CreateDraftListingRequestSerializerInterface::KEY_IMAGE_IDS, $imageIds, ['test-imageIds' => 'test-imageIds-value']],
                    [CreateDraftListingRequestSerializerInterface::KEY_PRODUCTION_PARTNER_IDS, $productionPartnerIds, ['test-productionPartnerIds' => 'test-productionPartnerIds-value']],
                ]
            );

        $createDraftListingRequest = (new CreateDraftListingRequest($quantity, $title, $description, $price, $whoMade, $whenMade, $taxonomyId))
            ->setQuantity($quantity)
            ->setTitle($title)
            ->setDescription($description)
            ->setPrice($price)
            ->setWhoMade($whoMade)
            ->setWhenMade($whenMade)
            ->setTaxonomyId($taxonomyId)
            ->setImageIds($imageIds)
            ->setIsCustomizable($isCustomizable)
            ->setIsSupply($isSupply)
            ->setIsTaxable($isTaxable)
            ->setItemDimensionsUnit($itemDimensionsUnit)
            ->setItemHeight($itemHeight)
            ->setItemLength($itemLength)
            ->setItemWeight($itemWeight)
            ->setItemWeightUnit($itemWeightUnit)
            ->setItemWidth($itemWidth)
            ->setMaterials($materials)
            ->setProcessingMax($processingMax)
            ->setProcessingMin($processingMin)
            ->setProductionPartnerIds($productionPartnerIds)
            ->setReadinessStateId($readinessStateId)
            ->setReturnPolicyId($returnPolicyId)
            ->setShippingProfileId($shippingProfileId)
            ->setShopSectionId($shopSectionId)
            ->setShouldAutoRenew($shouldAutoRenew)
            ->setStyles($styles)
            ->setTags($tags)
            ->setType($type)
            ->setEcgtAfterSalesServiceInfo($ecgtAfterSalesServiceInfo)
            ->setEcgtGaranBrand($ecgtGaranBrand)
            ->setEcgtGaranGuaranteeDetails($ecgtGaranGuaranteeDetails)
            ->setEcgtGaranModel($ecgtGaranModel)
            ->setEcgtGaranYears($ecgtGaranYears)
            ->setEcgtOtherCommercialGuaranteeDetails($ecgtOtherCommercialGuaranteeDetails)
            ->setEcgtSoftwareUpdateDetails($ecgtSoftwareUpdateDetails);

        $serializer = new CreateDraftListingRequestSerializer($formValueEncoder);

        $expected = [
            CreateDraftListingRequestSerializerInterface::KEY_DESCRIPTION => $description,
            CreateDraftListingRequestSerializerInterface::KEY_ECGT_AFTER_SALES_SERVICE_INFO => $ecgtAfterSalesServiceInfo,
            CreateDraftListingRequestSerializerInterface::KEY_ECGT_GARAN_BRAND => $ecgtGaranBrand,
            CreateDraftListingRequestSerializerInterface::KEY_ECGT_GARAN_GUARANTEE_DETAILS => $ecgtGaranGuaranteeDetails,
            CreateDraftListingRequestSerializerInterface::KEY_ECGT_GARAN_MODEL => $ecgtGaranModel,
            CreateDraftListingRequestSerializerInterface::KEY_ECGT_GARAN_YEARS => 'test-int-19',
            CreateDraftListingRequestSerializerInterface::KEY_ECGT_OTHER_COMMERCIAL_GUARANTEE_DETAILS => $ecgtOtherCommercialGuaranteeDetails,
            CreateDraftListingRequestSerializerInterface::KEY_ECGT_SOFTWARE_UPDATE_DETAILS => $ecgtSoftwareUpdateDetails,
            'test-imageIds' => 'test-imageIds-value',
            CreateDraftListingRequestSerializerInterface::KEY_IS_CUSTOMIZABLE => 'test-bool-true',
            CreateDraftListingRequestSerializerInterface::KEY_IS_SUPPLY => 'test-bool-true',
            CreateDraftListingRequestSerializerInterface::KEY_IS_TAXABLE => 'test-bool-true',
            CreateDraftListingRequestSerializerInterface::KEY_ITEM_DIMENSIONS_UNIT => $itemDimensionsUnit,
            CreateDraftListingRequestSerializerInterface::KEY_ITEM_HEIGHT => 'test-float-6.5',
            CreateDraftListingRequestSerializerInterface::KEY_ITEM_LENGTH => 'test-float-7.5',
            CreateDraftListingRequestSerializerInterface::KEY_ITEM_WEIGHT => 'test-float-8.5',
            CreateDraftListingRequestSerializerInterface::KEY_ITEM_WEIGHT_UNIT => $itemWeightUnit,
            CreateDraftListingRequestSerializerInterface::KEY_ITEM_WIDTH => 'test-float-9.5',
            'test-materials' => 'test-materials-value',
            CreateDraftListingRequestSerializerInterface::KEY_PRICE => 'test-float-2.5',
            CreateDraftListingRequestSerializerInterface::KEY_PROCESSING_MAX => 'test-int-11',
            CreateDraftListingRequestSerializerInterface::KEY_PROCESSING_MIN => 'test-int-12',
            'test-productionPartnerIds' => 'test-productionPartnerIds-value',
            CreateDraftListingRequestSerializerInterface::KEY_QUANTITY => 'test-int-1',
            CreateDraftListingRequestSerializerInterface::KEY_READINESS_STATE_ID => 'test-int-15',
            CreateDraftListingRequestSerializerInterface::KEY_RETURN_POLICY_ID => 'test-int-16',
            CreateDraftListingRequestSerializerInterface::KEY_SHIPPING_PROFILE_ID => 'test-int-17',
            CreateDraftListingRequestSerializerInterface::KEY_SHOP_SECTION_ID => 'test-int-18',
            CreateDraftListingRequestSerializerInterface::KEY_SHOULD_AUTO_RENEW => 'test-bool-true',
            'test-styles' => 'test-styles-value',
            'test-tags' => 'test-tags-value',
            CreateDraftListingRequestSerializerInterface::KEY_TAXONOMY_ID => 'test-int-3',
            CreateDraftListingRequestSerializerInterface::KEY_TITLE => $title,
            CreateDraftListingRequestSerializerInterface::KEY_TYPE => $type,
            CreateDraftListingRequestSerializerInterface::KEY_WHEN_MADE => $whenMade,
            CreateDraftListingRequestSerializerInterface::KEY_WHO_MADE => $whoMade,
        ];

        self::assertSame($expected, $serializer->serialize($createDraftListingRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $quantity = 1;
        $title = 'test-title';
        $description = 'test-description';
        $price = 2.5;
        $whoMade = 'test-whoMade';
        $whenMade = 'test-whenMade';
        $taxonomyId = 3;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [3, 'test-int-3'],
                ]
            );
        $formValueEncoder->method('encodeFloat')
            ->willReturnMap(
                [
                    [2.5, 'test-float-2.5'],
                ]
            );

        $createDraftListingRequest = new CreateDraftListingRequest($quantity, $title, $description, $price, $whoMade, $whenMade, $taxonomyId);

        $serializer = new CreateDraftListingRequestSerializer($formValueEncoder);

        $expected = [
            CreateDraftListingRequestSerializerInterface::KEY_DESCRIPTION => $description,
            CreateDraftListingRequestSerializerInterface::KEY_PRICE => 'test-float-2.5',
            CreateDraftListingRequestSerializerInterface::KEY_QUANTITY => 'test-int-1',
            CreateDraftListingRequestSerializerInterface::KEY_TAXONOMY_ID => 'test-int-3',
            CreateDraftListingRequestSerializerInterface::KEY_TITLE => $title,
            CreateDraftListingRequestSerializerInterface::KEY_WHEN_MADE => $whenMade,
            CreateDraftListingRequestSerializerInterface::KEY_WHO_MADE => $whoMade,
        ];

        self::assertSame($expected, $serializer->serialize($createDraftListingRequest));
    }
}
