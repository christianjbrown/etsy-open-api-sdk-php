<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\CreateDraftListingRequestInterface;

use function array_merge;

final class CreateDraftListingRequestSerializer implements CreateDraftListingRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $data = [];

        $data = self::applyDescription($data, $createDraftListingRequest);
        $data = $this->applyImageIds($data, $createDraftListingRequest);
        $data = $this->applyIsCustomizable($data, $createDraftListingRequest);
        $data = $this->applyIsSupply($data, $createDraftListingRequest);
        $data = $this->applyIsTaxable($data, $createDraftListingRequest);
        $data = self::applyItemDimensionsUnit($data, $createDraftListingRequest);
        $data = $this->applyItemHeight($data, $createDraftListingRequest);
        $data = $this->applyItemLength($data, $createDraftListingRequest);
        $data = $this->applyItemWeight($data, $createDraftListingRequest);
        $data = self::applyItemWeightUnit($data, $createDraftListingRequest);
        $data = $this->applyItemWidth($data, $createDraftListingRequest);
        $data = $this->applyMaterials($data, $createDraftListingRequest);
        $data = $this->applyPrice($data, $createDraftListingRequest);
        $data = $this->applyProcessingMax($data, $createDraftListingRequest);
        $data = $this->applyProcessingMin($data, $createDraftListingRequest);
        $data = $this->applyProductionPartnerIds($data, $createDraftListingRequest);
        $data = $this->applyQuantity($data, $createDraftListingRequest);
        $data = $this->applyReadinessStateId($data, $createDraftListingRequest);
        $data = $this->applyReturnPolicyId($data, $createDraftListingRequest);
        $data = $this->applyShippingProfileId($data, $createDraftListingRequest);
        $data = $this->applyShopSectionId($data, $createDraftListingRequest);
        $data = $this->applyShouldAutoRenew($data, $createDraftListingRequest);
        $data = $this->applyStyles($data, $createDraftListingRequest);
        $data = $this->applyTags($data, $createDraftListingRequest);
        $data = $this->applyTaxonomyId($data, $createDraftListingRequest);
        $data = self::applyTitle($data, $createDraftListingRequest);
        $data = self::applyType($data, $createDraftListingRequest);
        $data = self::applyWhenMade($data, $createDraftListingRequest);
        $data = self::applyWhoMade($data, $createDraftListingRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyDescription(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $data[self::KEY_DESCRIPTION] = $createDraftListingRequest->getDescription();

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyImageIds(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getImageIds();
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
    private function applyIsCustomizable(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getIsCustomizable();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_IS_CUSTOMIZABLE] = $this->formValueEncoder->encodeBool($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyIsSupply(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getIsSupply();
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
    private function applyIsTaxable(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getIsTaxable();
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
    private static function applyItemDimensionsUnit(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getItemDimensionsUnit();
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
    private function applyItemHeight(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getItemHeight();
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
    private function applyItemLength(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getItemLength();
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
    private function applyItemWeight(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getItemWeight();
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
    private static function applyItemWeightUnit(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getItemWeightUnit();
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
    private function applyItemWidth(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getItemWidth();
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
    private function applyMaterials(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getMaterials();
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
    private function applyPrice(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $data[self::KEY_PRICE] = $this->formValueEncoder->encodeFloat($createDraftListingRequest->getPrice());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyProcessingMax(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getProcessingMax();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_PROCESSING_MAX] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyProcessingMin(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getProcessingMin();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_PROCESSING_MIN] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyProductionPartnerIds(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getProductionPartnerIds();
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
    private function applyQuantity(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $data[self::KEY_QUANTITY] = $this->formValueEncoder->encodeInt($createDraftListingRequest->getQuantity());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyReadinessStateId(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getReadinessStateId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_READINESS_STATE_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyReturnPolicyId(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getReturnPolicyId();
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
    private function applyShippingProfileId(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getShippingProfileId();
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
    private function applyShopSectionId(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getShopSectionId();
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
    private function applyShouldAutoRenew(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getShouldAutoRenew();
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
    private function applyStyles(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getStyles();
        if (empty($value)) {
            return $data;
        }

        return array_merge($data, $this->formValueEncoder->encodeStringList(self::KEY_STYLES, $value));
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyTags(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getTags();
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
    private function applyTaxonomyId(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $data[self::KEY_TAXONOMY_ID] = $this->formValueEncoder->encodeInt($createDraftListingRequest->getTaxonomyId());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyTitle(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $data[self::KEY_TITLE] = $createDraftListingRequest->getTitle();

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyType(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $value = $createDraftListingRequest->getType();
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
    private static function applyWhenMade(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $data[self::KEY_WHEN_MADE] = $createDraftListingRequest->getWhenMade();

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyWhoMade(array $data, CreateDraftListingRequestInterface $createDraftListingRequest): array
    {
        $data[self::KEY_WHO_MADE] = $createDraftListingRequest->getWhoMade();

        return $data;
    }
}
