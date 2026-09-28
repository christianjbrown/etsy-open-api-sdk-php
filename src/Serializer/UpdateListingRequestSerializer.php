<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateListingRequestInterface;

use function array_merge;

final class UpdateListingRequestSerializer implements UpdateListingRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateListingRequestInterface $updateListingRequest): array
    {
        $data = [];

        $data = self::applyDescription($data, $updateListingRequest);
        $data = self::applyEcgtAfterSalesServiceInfo($data, $updateListingRequest);
        $data = self::applyEcgtGaranBrand($data, $updateListingRequest);
        $data = self::applyEcgtGaranGuaranteeDetails($data, $updateListingRequest);
        $data = self::applyEcgtGaranModel($data, $updateListingRequest);
        $data = $this->applyEcgtGaranYears($data, $updateListingRequest);
        $data = self::applyEcgtOtherCommercialGuaranteeDetails($data, $updateListingRequest);
        $data = self::applyEcgtSoftwareUpdateDetails($data, $updateListingRequest);
        $data = $this->applyFeaturedRank($data, $updateListingRequest);
        $data = $this->applyImageIds($data, $updateListingRequest);
        $data = $this->applyIsSupply($data, $updateListingRequest);
        $data = $this->applyIsTaxable($data, $updateListingRequest);
        $data = self::applyItemDimensionsUnit($data, $updateListingRequest);
        $data = $this->applyItemHeight($data, $updateListingRequest);
        $data = $this->applyItemLength($data, $updateListingRequest);
        $data = $this->applyItemWeight($data, $updateListingRequest);
        $data = self::applyItemWeightUnit($data, $updateListingRequest);
        $data = $this->applyItemWidth($data, $updateListingRequest);
        $data = $this->applyMaterials($data, $updateListingRequest);
        $data = $this->applyProductionPartnerIds($data, $updateListingRequest);
        $data = $this->applyReturnPolicyId($data, $updateListingRequest);
        $data = $this->applyShippingProfileId($data, $updateListingRequest);
        $data = $this->applyShopSectionId($data, $updateListingRequest);
        $data = $this->applyShouldAutoRenew($data, $updateListingRequest);
        $data = self::applyState($data, $updateListingRequest);
        $data = $this->applyTags($data, $updateListingRequest);
        $data = $this->applyTaxonomyId($data, $updateListingRequest);
        $data = self::applyTitle($data, $updateListingRequest);
        $data = self::applyType($data, $updateListingRequest);
        $data = self::applyWhenMade($data, $updateListingRequest);
        $data = self::applyWhoMade($data, $updateListingRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyDescription(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getDescription();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_DESCRIPTION] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyEcgtAfterSalesServiceInfo(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getEcgtAfterSalesServiceInfo();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ECGT_AFTER_SALES_SERVICE_INFO] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyEcgtGaranBrand(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getEcgtGaranBrand();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ECGT_GARAN_BRAND] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyEcgtGaranGuaranteeDetails(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getEcgtGaranGuaranteeDetails();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ECGT_GARAN_GUARANTEE_DETAILS] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyEcgtGaranModel(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getEcgtGaranModel();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ECGT_GARAN_MODEL] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyEcgtGaranYears(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getEcgtGaranYears();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ECGT_GARAN_YEARS] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyEcgtOtherCommercialGuaranteeDetails(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getEcgtOtherCommercialGuaranteeDetails();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ECGT_OTHER_COMMERCIAL_GUARANTEE_DETAILS] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyEcgtSoftwareUpdateDetails(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getEcgtSoftwareUpdateDetails();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ECGT_SOFTWARE_UPDATE_DETAILS] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyFeaturedRank(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getFeaturedRank();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_FEATURED_RANK] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyImageIds(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getImageIds();
        if (empty($value)) {
            return $data;
        }

        return array_merge($data, $this->formValueEncoder->encodeIntList(self::KEY_IMAGE_IDS, $value));
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyIsSupply(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getIsSupply();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_IS_SUPPLY] = $this->formValueEncoder->encodeBool($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyIsTaxable(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getIsTaxable();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_IS_TAXABLE] = $this->formValueEncoder->encodeBool($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyItemDimensionsUnit(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getItemDimensionsUnit();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ITEM_DIMENSIONS_UNIT] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyItemHeight(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getItemHeight();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ITEM_HEIGHT] = $this->formValueEncoder->encodeFloat($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyItemLength(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getItemLength();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ITEM_LENGTH] = $this->formValueEncoder->encodeFloat($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyItemWeight(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getItemWeight();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ITEM_WEIGHT] = $this->formValueEncoder->encodeFloat($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyItemWeightUnit(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getItemWeightUnit();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ITEM_WEIGHT_UNIT] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyItemWidth(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getItemWidth();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ITEM_WIDTH] = $this->formValueEncoder->encodeFloat($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyMaterials(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getMaterials();
        if (empty($value)) {
            return $data;
        }

        return array_merge($data, $this->formValueEncoder->encodeStringList(self::KEY_MATERIALS, $value));
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyProductionPartnerIds(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getProductionPartnerIds();
        if (empty($value)) {
            return $data;
        }

        return array_merge($data, $this->formValueEncoder->encodeIntList(self::KEY_PRODUCTION_PARTNER_IDS, $value));
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyReturnPolicyId(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getReturnPolicyId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_RETURN_POLICY_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyShippingProfileId(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getShippingProfileId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHIPPING_PROFILE_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyShopSectionId(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getShopSectionId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHOP_SECTION_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyShouldAutoRenew(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getShouldAutoRenew();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHOULD_AUTO_RENEW] = $this->formValueEncoder->encodeBool($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyState(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getState();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_STATE] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyTags(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getTags();
        if (empty($value)) {
            return $data;
        }

        return array_merge($data, $this->formValueEncoder->encodeStringList(self::KEY_TAGS, $value));
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyTaxonomyId(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getTaxonomyId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_TAXONOMY_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyTitle(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getTitle();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_TITLE] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyType(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getType();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_TYPE] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyWhenMade(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getWhenMade();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_WHEN_MADE] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyWhoMade(array $data, UpdateListingRequestInterface $updateListingRequest): array
    {
        $value = $updateListingRequest->getWhoMade();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_WHO_MADE] = $value;

        return $data;
    }
}
