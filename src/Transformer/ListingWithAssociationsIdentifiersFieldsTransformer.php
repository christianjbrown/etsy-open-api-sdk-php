<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function is_int;

final class ListingWithAssociationsIdentifiersFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        self::applyFeaturedRank($listing, $data);
        self::applyNumFavorers($listing, $data);
        self::applyProcessingMax($listing, $data);
        self::applyProcessingMin($listing, $data);
        self::applyQuantity($listing, $data);
        self::applyReadinessStateId($listing, $data);
        self::applyReturnPolicyId($listing, $data);
        self::applyShippingProfileId($listing, $data);
        self::applyShopId($listing, $data);
        self::applyShopSectionId($listing, $data);
        self::applyTaxonomyId($listing, $data);
        self::applyUserId($listing, $data);
        self::applyViews($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFeaturedRank(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_FEATURED_RANK])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_FEATURED_RANK])) {
            return;
        }
        $listing->setFeaturedRank($data[ListingWithAssociationsTransformerInterface::KEY_FEATURED_RANK]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyNumFavorers(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_NUM_FAVORERS])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_NUM_FAVORERS])) {
            return;
        }
        $listing->setNumFavorers($data[ListingWithAssociationsTransformerInterface::KEY_NUM_FAVORERS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyProcessingMax(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_PROCESSING_MAX])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_PROCESSING_MAX])) {
            return;
        }
        $listing->setProcessingMax($data[ListingWithAssociationsTransformerInterface::KEY_PROCESSING_MAX]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyProcessingMin(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_PROCESSING_MIN])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_PROCESSING_MIN])) {
            return;
        }
        $listing->setProcessingMin($data[ListingWithAssociationsTransformerInterface::KEY_PROCESSING_MIN]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyQuantity(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_QUANTITY])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_QUANTITY])) {
            return;
        }
        $listing->setQuantity($data[ListingWithAssociationsTransformerInterface::KEY_QUANTITY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReadinessStateId(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_READINESS_STATE_ID])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_READINESS_STATE_ID])) {
            return;
        }
        $listing->setReadinessStateId($data[ListingWithAssociationsTransformerInterface::KEY_READINESS_STATE_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReturnPolicyId(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_RETURN_POLICY_ID])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_RETURN_POLICY_ID])) {
            return;
        }
        $listing->setReturnPolicyId($data[ListingWithAssociationsTransformerInterface::KEY_RETURN_POLICY_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingProfileId(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_SHIPPING_PROFILE_ID])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_SHIPPING_PROFILE_ID])) {
            return;
        }
        $listing->setShippingProfileId($data[ListingWithAssociationsTransformerInterface::KEY_SHIPPING_PROFILE_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopId(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_SHOP_ID])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_SHOP_ID])) {
            return;
        }
        $listing->setShopId($data[ListingWithAssociationsTransformerInterface::KEY_SHOP_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopSectionId(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_SHOP_SECTION_ID])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_SHOP_SECTION_ID])) {
            return;
        }
        $listing->setShopSectionId($data[ListingWithAssociationsTransformerInterface::KEY_SHOP_SECTION_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTaxonomyId(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_TAXONOMY_ID])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_TAXONOMY_ID])) {
            return;
        }
        $listing->setTaxonomyId($data[ListingWithAssociationsTransformerInterface::KEY_TAXONOMY_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUserId(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_USER_ID])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_USER_ID])) {
            return;
        }
        $listing->setUserId($data[ListingWithAssociationsTransformerInterface::KEY_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyViews(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_VIEWS])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_VIEWS])) {
            return;
        }
        $listing->setViews($data[ListingWithAssociationsTransformerInterface::KEY_VIEWS]);
    }
}
