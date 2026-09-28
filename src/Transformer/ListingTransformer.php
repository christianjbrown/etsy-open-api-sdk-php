<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Listing;
use ChristianBrown\Etsy\Model\ListingInterface;

use function array_values;
use function count;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function sprintf;

final class ListingTransformer implements ListingTransformerInterface
{
    private MoneyTransformerInterface $moneyTransformer;

    public function __construct(MoneyTransformerInterface $moneyTransformer)
    {
        $this->moneyTransformer = $moneyTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingInterface
    {
        if (!isset($data[self::KEY_LISTING_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_ID));
        }
        if (!is_int($data[self::KEY_LISTING_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_ID));
        }
        $listing = new Listing($data[self::KEY_LISTING_ID]);

        $this->applyConvertedPrice($listing, $data);
        self::applyCreatedTimestamp($listing, $data);
        self::applyCreationTimestamp($listing, $data);
        self::applyDescription($listing, $data);
        self::applyEcgtAfterSalesServiceInfo($listing, $data);
        self::applyEcgtCommercialGuaranteeEnabled($listing, $data);
        self::applyEcgtGaranBrand($listing, $data);
        self::applyEcgtGaranGuaranteeDetails($listing, $data);
        self::applyEcgtGaranModel($listing, $data);
        self::applyEcgtGaranYears($listing, $data);
        self::applyEcgtOtherCommercialGuaranteeDetails($listing, $data);
        self::applyEcgtSoftwareUpdateDetails($listing, $data);
        self::applyEndingTimestamp($listing, $data);
        self::applyFeaturedRank($listing, $data);
        self::applyFileData($listing, $data);
        self::applyHasVariations($listing, $data);
        self::applyIsCustomizable($listing, $data);
        self::applyIsPersonalizable($listing, $data);
        self::applyIsPrivate($listing, $data);
        self::applyIsSupply($listing, $data);
        self::applyIsTaxable($listing, $data);
        self::applyItemDimensionsUnit($listing, $data);
        self::applyItemHeight($listing, $data);
        self::applyItemLength($listing, $data);
        self::applyItemWeight($listing, $data);
        self::applyItemWeightUnit($listing, $data);
        self::applyItemWidth($listing, $data);
        self::applyLanguage($listing, $data);
        self::applyLastModifiedTimestamp($listing, $data);
        self::applyListingType($listing, $data);
        self::applyMaterials($listing, $data);
        self::applyNonTaxable($listing, $data);
        self::applyNumFavorers($listing, $data);
        self::applyOriginalCreationTimestamp($listing, $data);
        $this->applyPrice($listing, $data);
        self::applyProcessingMax($listing, $data);
        self::applyProcessingMin($listing, $data);
        self::applyQuantity($listing, $data);
        self::applyReadinessStateId($listing, $data);
        self::applyReturnPolicyId($listing, $data);
        self::applyRichDescription($listing, $data);
        self::applyShippingProfileId($listing, $data);
        self::applyShopId($listing, $data);
        self::applyShopSectionId($listing, $data);
        self::applyShouldAutoRenew($listing, $data);
        self::applyState($listing, $data);
        self::applyStateTimestamp($listing, $data);
        self::applyStyle($listing, $data);
        self::applySuggestedTitle($listing, $data);
        self::applyTags($listing, $data);
        self::applyTaxonomyId($listing, $data);
        self::applyTitle($listing, $data);
        self::applyUpdatedTimestamp($listing, $data);
        self::applyUrl($listing, $data);
        self::applyUserId($listing, $data);
        self::applyWhenMade($listing, $data);
        self::applyWhoMade($listing, $data);

        return $listing;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyConvertedPrice(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_CONVERTED_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_CONVERTED_PRICE])) {
            return;
        }
        $listing->setConvertedPrice($this->moneyTransformer->transform($data[self::KEY_CONVERTED_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $listing->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreationTimestamp(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_CREATION_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATION_TIMESTAMP])) {
            return;
        }
        $listing->setCreationTimestamp($data[self::KEY_CREATION_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDescription(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_DESCRIPTION])) {
            return;
        }
        if (!is_string($data[self::KEY_DESCRIPTION])) {
            return;
        }
        $listing->setDescription($data[self::KEY_DESCRIPTION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtAfterSalesServiceInfo(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_ECGT_AFTER_SALES_SERVICE_INFO])) {
            return;
        }
        if (!is_string($data[self::KEY_ECGT_AFTER_SALES_SERVICE_INFO])) {
            return;
        }
        $listing->setEcgtAfterSalesServiceInfo($data[self::KEY_ECGT_AFTER_SALES_SERVICE_INFO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtCommercialGuaranteeEnabled(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_ECGT_COMMERCIAL_GUARANTEE_ENABLED])) {
            return;
        }
        if (!is_bool($data[self::KEY_ECGT_COMMERCIAL_GUARANTEE_ENABLED])) {
            return;
        }
        $listing->setEcgtCommercialGuaranteeEnabled($data[self::KEY_ECGT_COMMERCIAL_GUARANTEE_ENABLED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtGaranBrand(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_ECGT_GARAN_BRAND])) {
            return;
        }
        if (!is_string($data[self::KEY_ECGT_GARAN_BRAND])) {
            return;
        }
        $listing->setEcgtGaranBrand($data[self::KEY_ECGT_GARAN_BRAND]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtGaranGuaranteeDetails(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_ECGT_GARAN_GUARANTEE_DETAILS])) {
            return;
        }
        if (!is_string($data[self::KEY_ECGT_GARAN_GUARANTEE_DETAILS])) {
            return;
        }
        $listing->setEcgtGaranGuaranteeDetails($data[self::KEY_ECGT_GARAN_GUARANTEE_DETAILS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtGaranModel(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_ECGT_GARAN_MODEL])) {
            return;
        }
        if (!is_string($data[self::KEY_ECGT_GARAN_MODEL])) {
            return;
        }
        $listing->setEcgtGaranModel($data[self::KEY_ECGT_GARAN_MODEL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtGaranYears(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_ECGT_GARAN_YEARS])) {
            return;
        }
        if (!is_int($data[self::KEY_ECGT_GARAN_YEARS])) {
            return;
        }
        $listing->setEcgtGaranYears($data[self::KEY_ECGT_GARAN_YEARS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtOtherCommercialGuaranteeDetails(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_ECGT_OTHER_COMMERCIAL_GUARANTEE_DETAILS])) {
            return;
        }
        if (!is_string($data[self::KEY_ECGT_OTHER_COMMERCIAL_GUARANTEE_DETAILS])) {
            return;
        }
        $listing->setEcgtOtherCommercialGuaranteeDetails($data[self::KEY_ECGT_OTHER_COMMERCIAL_GUARANTEE_DETAILS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtSoftwareUpdateDetails(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_ECGT_SOFTWARE_UPDATE_DETAILS])) {
            return;
        }
        if (!is_string($data[self::KEY_ECGT_SOFTWARE_UPDATE_DETAILS])) {
            return;
        }
        $listing->setEcgtSoftwareUpdateDetails($data[self::KEY_ECGT_SOFTWARE_UPDATE_DETAILS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEndingTimestamp(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_ENDING_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_ENDING_TIMESTAMP])) {
            return;
        }
        $listing->setEndingTimestamp($data[self::KEY_ENDING_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFeaturedRank(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_FEATURED_RANK])) {
            return;
        }
        if (!is_int($data[self::KEY_FEATURED_RANK])) {
            return;
        }
        $listing->setFeaturedRank($data[self::KEY_FEATURED_RANK]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFileData(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_FILE_DATA])) {
            return;
        }
        if (!is_string($data[self::KEY_FILE_DATA])) {
            return;
        }
        $listing->setFileData($data[self::KEY_FILE_DATA]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyHasVariations(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_HAS_VARIATIONS])) {
            return;
        }
        if (!is_bool($data[self::KEY_HAS_VARIATIONS])) {
            return;
        }
        $listing->setHasVariations($data[self::KEY_HAS_VARIATIONS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsCustomizable(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_IS_CUSTOMIZABLE])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_CUSTOMIZABLE])) {
            return;
        }
        $listing->setIsCustomizable($data[self::KEY_IS_CUSTOMIZABLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsPersonalizable(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_IS_PERSONALIZABLE])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_PERSONALIZABLE])) {
            return;
        }
        $listing->setIsPersonalizable($data[self::KEY_IS_PERSONALIZABLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsPrivate(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_IS_PRIVATE])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_PRIVATE])) {
            return;
        }
        $listing->setIsPrivate($data[self::KEY_IS_PRIVATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsSupply(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_IS_SUPPLY])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_SUPPLY])) {
            return;
        }
        $listing->setIsSupply($data[self::KEY_IS_SUPPLY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsTaxable(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_IS_TAXABLE])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_TAXABLE])) {
            return;
        }
        $listing->setIsTaxable($data[self::KEY_IS_TAXABLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemDimensionsUnit(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_ITEM_DIMENSIONS_UNIT])) {
            return;
        }
        if (!is_string($data[self::KEY_ITEM_DIMENSIONS_UNIT])) {
            return;
        }
        $listing->setItemDimensionsUnit($data[self::KEY_ITEM_DIMENSIONS_UNIT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemHeight(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_ITEM_HEIGHT])) {
            return;
        }
        $value = self::toFloat($data[self::KEY_ITEM_HEIGHT]);
        if (null === $value) {
            return;
        }
        $listing->setItemHeight($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemLength(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_ITEM_LENGTH])) {
            return;
        }
        $value = self::toFloat($data[self::KEY_ITEM_LENGTH]);
        if (null === $value) {
            return;
        }
        $listing->setItemLength($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemWeight(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_ITEM_WEIGHT])) {
            return;
        }
        $value = self::toFloat($data[self::KEY_ITEM_WEIGHT]);
        if (null === $value) {
            return;
        }
        $listing->setItemWeight($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemWeightUnit(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_ITEM_WEIGHT_UNIT])) {
            return;
        }
        if (!is_string($data[self::KEY_ITEM_WEIGHT_UNIT])) {
            return;
        }
        $listing->setItemWeightUnit($data[self::KEY_ITEM_WEIGHT_UNIT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemWidth(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_ITEM_WIDTH])) {
            return;
        }
        $value = self::toFloat($data[self::KEY_ITEM_WIDTH]);
        if (null === $value) {
            return;
        }
        $listing->setItemWidth($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLanguage(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_LANGUAGE])) {
            return;
        }
        if (!is_string($data[self::KEY_LANGUAGE])) {
            return;
        }
        $listing->setLanguage($data[self::KEY_LANGUAGE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLastModifiedTimestamp(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_LAST_MODIFIED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_LAST_MODIFIED_TIMESTAMP])) {
            return;
        }
        $listing->setLastModifiedTimestamp($data[self::KEY_LAST_MODIFIED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyListingType(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_LISTING_TYPE])) {
            return;
        }
        if (!is_string($data[self::KEY_LISTING_TYPE])) {
            return;
        }
        $listing->setListingType($data[self::KEY_LISTING_TYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMaterials(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_MATERIALS])) {
            return;
        }
        if (!is_array($data[self::KEY_MATERIALS])) {
            return;
        }
        $values = [];
        $items = array_values($data[self::KEY_MATERIALS]);
        for ($i = 0, $itemCount = count($items); $i < $itemCount; ++$i) {
            if (!is_string($items[$i])) {
                continue;
            }
            $values[] = $items[$i];
        }
        $listing->setMaterials($values);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyNonTaxable(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_NON_TAXABLE])) {
            return;
        }
        if (!is_bool($data[self::KEY_NON_TAXABLE])) {
            return;
        }
        $listing->setNonTaxable($data[self::KEY_NON_TAXABLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyNumFavorers(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_NUM_FAVORERS])) {
            return;
        }
        if (!is_int($data[self::KEY_NUM_FAVORERS])) {
            return;
        }
        $listing->setNumFavorers($data[self::KEY_NUM_FAVORERS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyOriginalCreationTimestamp(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_ORIGINAL_CREATION_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_ORIGINAL_CREATION_TIMESTAMP])) {
            return;
        }
        $listing->setOriginalCreationTimestamp($data[self::KEY_ORIGINAL_CREATION_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPrice(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_PRICE])) {
            return;
        }
        $listing->setPrice($this->moneyTransformer->transform($data[self::KEY_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyProcessingMax(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_PROCESSING_MAX])) {
            return;
        }
        if (!is_int($data[self::KEY_PROCESSING_MAX])) {
            return;
        }
        $listing->setProcessingMax($data[self::KEY_PROCESSING_MAX]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyProcessingMin(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_PROCESSING_MIN])) {
            return;
        }
        if (!is_int($data[self::KEY_PROCESSING_MIN])) {
            return;
        }
        $listing->setProcessingMin($data[self::KEY_PROCESSING_MIN]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyQuantity(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_QUANTITY])) {
            return;
        }
        if (!is_int($data[self::KEY_QUANTITY])) {
            return;
        }
        $listing->setQuantity($data[self::KEY_QUANTITY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReadinessStateId(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_READINESS_STATE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_READINESS_STATE_ID])) {
            return;
        }
        $listing->setReadinessStateId($data[self::KEY_READINESS_STATE_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReturnPolicyId(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_RETURN_POLICY_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_RETURN_POLICY_ID])) {
            return;
        }
        $listing->setReturnPolicyId($data[self::KEY_RETURN_POLICY_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyRichDescription(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_RICH_DESCRIPTION])) {
            return;
        }
        if (!is_string($data[self::KEY_RICH_DESCRIPTION])) {
            return;
        }
        $listing->setRichDescription($data[self::KEY_RICH_DESCRIPTION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingProfileId(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_SHIPPING_PROFILE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPPING_PROFILE_ID])) {
            return;
        }
        $listing->setShippingProfileId($data[self::KEY_SHIPPING_PROFILE_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopId(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_SHOP_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHOP_ID])) {
            return;
        }
        $listing->setShopId($data[self::KEY_SHOP_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopSectionId(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_SHOP_SECTION_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHOP_SECTION_ID])) {
            return;
        }
        $listing->setShopSectionId($data[self::KEY_SHOP_SECTION_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShouldAutoRenew(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_SHOULD_AUTO_RENEW])) {
            return;
        }
        if (!is_bool($data[self::KEY_SHOULD_AUTO_RENEW])) {
            return;
        }
        $listing->setShouldAutoRenew($data[self::KEY_SHOULD_AUTO_RENEW]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyState(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_STATE])) {
            return;
        }
        if (!is_string($data[self::KEY_STATE])) {
            return;
        }
        $listing->setState($data[self::KEY_STATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyStateTimestamp(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_STATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_STATE_TIMESTAMP])) {
            return;
        }
        $listing->setStateTimestamp($data[self::KEY_STATE_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyStyle(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_STYLE])) {
            return;
        }
        if (!is_array($data[self::KEY_STYLE])) {
            return;
        }
        $values = [];
        $items = array_values($data[self::KEY_STYLE]);
        for ($i = 0, $itemCount = count($items); $i < $itemCount; ++$i) {
            if (!is_string($items[$i])) {
                continue;
            }
            $values[] = $items[$i];
        }
        $listing->setStyle($values);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySuggestedTitle(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_SUGGESTED_TITLE])) {
            return;
        }
        if (!is_string($data[self::KEY_SUGGESTED_TITLE])) {
            return;
        }
        $listing->setSuggestedTitle($data[self::KEY_SUGGESTED_TITLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTags(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_TAGS])) {
            return;
        }
        if (!is_array($data[self::KEY_TAGS])) {
            return;
        }
        $values = [];
        $items = array_values($data[self::KEY_TAGS]);
        for ($i = 0, $itemCount = count($items); $i < $itemCount; ++$i) {
            if (!is_string($items[$i])) {
                continue;
            }
            $values[] = $items[$i];
        }
        $listing->setTags($values);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTaxonomyId(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_TAXONOMY_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_TAXONOMY_ID])) {
            return;
        }
        $listing->setTaxonomyId($data[self::KEY_TAXONOMY_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTitle(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_TITLE])) {
            return;
        }
        if (!is_string($data[self::KEY_TITLE])) {
            return;
        }
        $listing->setTitle($data[self::KEY_TITLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdatedTimestamp(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        $listing->setUpdatedTimestamp($data[self::KEY_UPDATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUrl(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_URL])) {
            return;
        }
        if (!is_string($data[self::KEY_URL])) {
            return;
        }
        $listing->setUrl($data[self::KEY_URL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUserId(Listing $listing, array $data): void
    {
        if (!isset($data[self::KEY_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_USER_ID])) {
            return;
        }
        $listing->setUserId($data[self::KEY_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyWhenMade(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_WHEN_MADE])) {
            return;
        }
        if (!is_string($data[self::KEY_WHEN_MADE])) {
            return;
        }
        $listing->setWhenMade($data[self::KEY_WHEN_MADE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyWhoMade(Listing $listing, array $data): void
    {
        if (empty($data[self::KEY_WHO_MADE])) {
            return;
        }
        if (!is_string($data[self::KEY_WHO_MADE])) {
            return;
        }
        $listing->setWhoMade($data[self::KEY_WHO_MADE]);
    }

    private static function toFloat(mixed $value): ?float
    {
        if (is_int($value)) {
            return (float) $value;
        }
        if (is_float($value)) {
            return $value;
        }

        return null;
    }
}
