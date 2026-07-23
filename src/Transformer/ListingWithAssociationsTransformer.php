<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingWithAssociations;
use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function array_keys;
use function array_values;
use function count;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function sprintf;

final class ListingWithAssociationsTransformer implements ListingWithAssociationsTransformerInterface
{
    private ListingBuyerPriceTransformerInterface $listingBuyerPriceTransformer;
    private ListingImagesTransformerInterface $listingImagesTransformer;
    private ListingInventoryTransformerInterface $listingInventoryTransformer;
    private ListingPersonalizationTransformerInterface $listingPersonalizationTransformer;
    private ListingTranslationTransformerInterface $listingTranslationTransformer;
    private ListingVideosTransformerInterface $listingVideosTransformer;
    private MoneyTransformerInterface $moneyTransformer;
    private ShopProductionPartnersTransformerInterface $shopProductionPartnersTransformer;
    private ShopShippingProfileTransformerInterface $shopShippingProfileTransformer;
    private ShopTransformerInterface $shopTransformer;
    private UserTransformerInterface $userTransformer;

    public function __construct(ListingBuyerPriceTransformerInterface $listingBuyerPriceTransformer, ListingImagesTransformerInterface $listingImagesTransformer, ListingInventoryTransformerInterface $listingInventoryTransformer, ListingPersonalizationTransformerInterface $listingPersonalizationTransformer, ListingTranslationTransformerInterface $listingTranslationTransformer, ListingVideosTransformerInterface $listingVideosTransformer, MoneyTransformerInterface $moneyTransformer, ShopProductionPartnersTransformerInterface $shopProductionPartnersTransformer, ShopShippingProfileTransformerInterface $shopShippingProfileTransformer, ShopTransformerInterface $shopTransformer, UserTransformerInterface $userTransformer)
    {
        $this->listingBuyerPriceTransformer = $listingBuyerPriceTransformer;
        $this->listingImagesTransformer = $listingImagesTransformer;
        $this->listingInventoryTransformer = $listingInventoryTransformer;
        $this->listingPersonalizationTransformer = $listingPersonalizationTransformer;
        $this->listingTranslationTransformer = $listingTranslationTransformer;
        $this->listingVideosTransformer = $listingVideosTransformer;
        $this->moneyTransformer = $moneyTransformer;
        $this->shopProductionPartnersTransformer = $shopProductionPartnersTransformer;
        $this->shopShippingProfileTransformer = $shopShippingProfileTransformer;
        $this->shopTransformer = $shopTransformer;
        $this->userTransformer = $userTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingWithAssociationsInterface
    {
        if (!isset($data[self::KEY_LISTING_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_ID));
        }
        if (!is_int($data[self::KEY_LISTING_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_ID));
        }
        $listing = new ListingWithAssociations($data[self::KEY_LISTING_ID]);

        $this->applyBuyerPrice($listing, $data);
        $this->applyConvertedPrice($listing, $data);
        self::applyCreatedTimestamp($listing, $data);
        self::applyCreationTimestamp($listing, $data);
        self::applyDescription($listing, $data);
        self::applyEndingTimestamp($listing, $data);
        self::applyFeaturedRank($listing, $data);
        self::applyFileData($listing, $data);
        self::applyHasVariations($listing, $data);
        $this->applyImages($listing, $data);
        $this->applyInventory($listing, $data);
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
        $this->applyPersonalization($listing, $data);
        $this->applyPrice($listing, $data);
        self::applyProcessingMax($listing, $data);
        self::applyProcessingMin($listing, $data);
        $this->applyProductionPartners($listing, $data);
        self::applyQuantity($listing, $data);
        self::applyReadinessStateId($listing, $data);
        self::applyReturnPolicyId($listing, $data);
        self::applyRichDescription($listing, $data);
        $this->applyShippingProfile($listing, $data);
        self::applyShippingProfileId($listing, $data);
        $this->applyShop($listing, $data);
        self::applyShopId($listing, $data);
        self::applyShopSectionId($listing, $data);
        self::applyShouldAutoRenew($listing, $data);
        self::applySkus($listing, $data);
        self::applyState($listing, $data);
        self::applyStateTimestamp($listing, $data);
        self::applyStyle($listing, $data);
        self::applySuggestedTitle($listing, $data);
        self::applyTags($listing, $data);
        self::applyTaxonomyId($listing, $data);
        self::applyTitle($listing, $data);
        $this->applyTranslations($listing, $data);
        self::applyUpdatedTimestamp($listing, $data);
        self::applyUrl($listing, $data);
        $this->applyUser($listing, $data);
        self::applyUserId($listing, $data);
        $this->applyVideos($listing, $data);
        self::applyViews($listing, $data);
        self::applyWhenMade($listing, $data);
        self::applyWhoMade($listing, $data);

        return $listing;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyBuyerPrice(ListingWithAssociations $listing, array $data): void
    {
        if (empty($data[self::KEY_BUYER_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_BUYER_PRICE])) {
            return;
        }
        $listing->setBuyerPrice($this->listingBuyerPriceTransformer->transform($data[self::KEY_BUYER_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyConvertedPrice(ListingWithAssociations $listing, array $data): void
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
    private static function applyCreatedTimestamp(ListingWithAssociations $listing, array $data): void
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
    private static function applyCreationTimestamp(ListingWithAssociations $listing, array $data): void
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
    private static function applyDescription(ListingWithAssociations $listing, array $data): void
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
    private static function applyEndingTimestamp(ListingWithAssociations $listing, array $data): void
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
    private static function applyFeaturedRank(ListingWithAssociations $listing, array $data): void
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
    private static function applyFileData(ListingWithAssociations $listing, array $data): void
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
    private static function applyHasVariations(ListingWithAssociations $listing, array $data): void
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
    private function applyImages(ListingWithAssociations $listing, array $data): void
    {
        if (empty($data[self::KEY_IMAGES])) {
            return;
        }
        if (!is_array($data[self::KEY_IMAGES])) {
            return;
        }
        $listing->setImages($this->listingImagesTransformer->transform($data[self::KEY_IMAGES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyInventory(ListingWithAssociations $listing, array $data): void
    {
        if (empty($data[self::KEY_INVENTORY])) {
            return;
        }
        if (!is_array($data[self::KEY_INVENTORY])) {
            return;
        }
        $listing->setInventory($this->listingInventoryTransformer->transform($data[self::KEY_INVENTORY]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsCustomizable(ListingWithAssociations $listing, array $data): void
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
    private static function applyIsPersonalizable(ListingWithAssociations $listing, array $data): void
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
    private static function applyIsPrivate(ListingWithAssociations $listing, array $data): void
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
    private static function applyIsSupply(ListingWithAssociations $listing, array $data): void
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
    private static function applyIsTaxable(ListingWithAssociations $listing, array $data): void
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
    private static function applyItemDimensionsUnit(ListingWithAssociations $listing, array $data): void
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
    private static function applyItemHeight(ListingWithAssociations $listing, array $data): void
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
    private static function applyItemLength(ListingWithAssociations $listing, array $data): void
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
    private static function applyItemWeight(ListingWithAssociations $listing, array $data): void
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
    private static function applyItemWeightUnit(ListingWithAssociations $listing, array $data): void
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
    private static function applyItemWidth(ListingWithAssociations $listing, array $data): void
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
    private static function applyLanguage(ListingWithAssociations $listing, array $data): void
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
    private static function applyLastModifiedTimestamp(ListingWithAssociations $listing, array $data): void
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
    private static function applyListingType(ListingWithAssociations $listing, array $data): void
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
    private static function applyMaterials(ListingWithAssociations $listing, array $data): void
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
    private static function applyNonTaxable(ListingWithAssociations $listing, array $data): void
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
    private static function applyNumFavorers(ListingWithAssociations $listing, array $data): void
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
    private static function applyOriginalCreationTimestamp(ListingWithAssociations $listing, array $data): void
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
    private function applyPersonalization(ListingWithAssociations $listing, array $data): void
    {
        if (empty($data[self::KEY_PERSONALIZATION])) {
            return;
        }
        if (!is_array($data[self::KEY_PERSONALIZATION])) {
            return;
        }
        $listing->setPersonalization($this->listingPersonalizationTransformer->transform($data[self::KEY_PERSONALIZATION]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPrice(ListingWithAssociations $listing, array $data): void
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
    private static function applyProcessingMax(ListingWithAssociations $listing, array $data): void
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
    private static function applyProcessingMin(ListingWithAssociations $listing, array $data): void
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
    private function applyProductionPartners(ListingWithAssociations $listing, array $data): void
    {
        if (empty($data[self::KEY_PRODUCTION_PARTNERS])) {
            return;
        }
        if (!is_array($data[self::KEY_PRODUCTION_PARTNERS])) {
            return;
        }
        $listing->setProductionPartners($this->shopProductionPartnersTransformer->transform($data[self::KEY_PRODUCTION_PARTNERS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyQuantity(ListingWithAssociations $listing, array $data): void
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
    private static function applyReadinessStateId(ListingWithAssociations $listing, array $data): void
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
    private static function applyReturnPolicyId(ListingWithAssociations $listing, array $data): void
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
    private static function applyRichDescription(ListingWithAssociations $listing, array $data): void
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
    private function applyShippingProfile(ListingWithAssociations $listing, array $data): void
    {
        if (empty($data[self::KEY_SHIPPING_PROFILE])) {
            return;
        }
        if (!is_array($data[self::KEY_SHIPPING_PROFILE])) {
            return;
        }
        $listing->setShippingProfile($this->shopShippingProfileTransformer->transform($data[self::KEY_SHIPPING_PROFILE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingProfileId(ListingWithAssociations $listing, array $data): void
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
    private function applyShop(ListingWithAssociations $listing, array $data): void
    {
        if (empty($data[self::KEY_SHOP])) {
            return;
        }
        if (!is_array($data[self::KEY_SHOP])) {
            return;
        }
        $listing->setShop($this->shopTransformer->transform($data[self::KEY_SHOP]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopId(ListingWithAssociations $listing, array $data): void
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
    private static function applyShopSectionId(ListingWithAssociations $listing, array $data): void
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
    private static function applyShouldAutoRenew(ListingWithAssociations $listing, array $data): void
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
    private static function applySkus(ListingWithAssociations $listing, array $data): void
    {
        if (!isset($data[self::KEY_SKUS])) {
            return;
        }
        if (!is_array($data[self::KEY_SKUS])) {
            return;
        }
        $values = [];
        $items = array_values($data[self::KEY_SKUS]);
        for ($i = 0, $itemCount = count($items); $i < $itemCount; ++$i) {
            if (!is_string($items[$i])) {
                continue;
            }
            $values[] = $items[$i];
        }
        $listing->setSkus($values);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyState(ListingWithAssociations $listing, array $data): void
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
    private static function applyStateTimestamp(ListingWithAssociations $listing, array $data): void
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
    private static function applyStyle(ListingWithAssociations $listing, array $data): void
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
    private static function applySuggestedTitle(ListingWithAssociations $listing, array $data): void
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
    private static function applyTags(ListingWithAssociations $listing, array $data): void
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
    private static function applyTaxonomyId(ListingWithAssociations $listing, array $data): void
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
    private static function applyTitle(ListingWithAssociations $listing, array $data): void
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
    private function applyTranslations(ListingWithAssociations $listing, array $data): void
    {
        if (!isset($data[self::KEY_TRANSLATIONS])) {
            return;
        }
        if (!is_array($data[self::KEY_TRANSLATIONS])) {
            return;
        }
        $translations = [];
        $languages = array_keys($data[self::KEY_TRANSLATIONS]);
        for ($i = 0, $languageCount = count($languages); $i < $languageCount; ++$i) {
            $translationData = $data[self::KEY_TRANSLATIONS][$languages[$i]];
            if (!is_array($translationData)) {
                continue;
            }
            $translations[$languages[$i]] = $this->listingTranslationTransformer->transform($translationData);
        }
        $listing->setTranslations($translations);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdatedTimestamp(ListingWithAssociations $listing, array $data): void
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
    private static function applyUrl(ListingWithAssociations $listing, array $data): void
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
    private function applyUser(ListingWithAssociations $listing, array $data): void
    {
        if (empty($data[self::KEY_USER])) {
            return;
        }
        if (!is_array($data[self::KEY_USER])) {
            return;
        }
        $listing->setUser($this->userTransformer->transform($data[self::KEY_USER]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUserId(ListingWithAssociations $listing, array $data): void
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
    private function applyVideos(ListingWithAssociations $listing, array $data): void
    {
        if (empty($data[self::KEY_VIDEOS])) {
            return;
        }
        if (!is_array($data[self::KEY_VIDEOS])) {
            return;
        }
        $listing->setVideos($this->listingVideosTransformer->transform($data[self::KEY_VIDEOS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyViews(ListingWithAssociations $listing, array $data): void
    {
        if (!isset($data[self::KEY_VIEWS])) {
            return;
        }
        if (!is_int($data[self::KEY_VIEWS])) {
            return;
        }
        $listing->setViews($data[self::KEY_VIEWS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyWhenMade(ListingWithAssociations $listing, array $data): void
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
    private static function applyWhoMade(ListingWithAssociations $listing, array $data): void
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
