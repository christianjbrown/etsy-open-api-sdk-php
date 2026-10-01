<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function is_string;

final class ListingWithAssociationsDimensionsFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        self::applyItemDimensionsUnit($listing, $data);
        self::applyItemHeight($listing, $data);
        self::applyItemLength($listing, $data);
        self::applyItemWeight($listing, $data);
        self::applyItemWeightUnit($listing, $data);
        self::applyItemWidth($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemDimensionsUnit(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_DIMENSIONS_UNIT])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_DIMENSIONS_UNIT])) {
            return;
        }
        $listing->setItemDimensionsUnit($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_DIMENSIONS_UNIT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemHeight(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_HEIGHT])) {
            return;
        }
        $value = self::toFloat($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_HEIGHT]);
        if (null === $value) {
            return;
        }
        $listing->setItemHeight($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemLength(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_LENGTH])) {
            return;
        }
        $value = self::toFloat($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_LENGTH]);
        if (null === $value) {
            return;
        }
        $listing->setItemLength($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemWeight(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_WEIGHT])) {
            return;
        }
        $value = self::toFloat($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_WEIGHT]);
        if (null === $value) {
            return;
        }
        $listing->setItemWeight($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemWeightUnit(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_WEIGHT_UNIT])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_WEIGHT_UNIT])) {
            return;
        }
        $listing->setItemWeightUnit($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_WEIGHT_UNIT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyItemWidth(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_WIDTH])) {
            return;
        }
        $value = self::toFloat($data[ListingWithAssociationsTransformerInterface::KEY_ITEM_WIDTH]);
        if (null === $value) {
            return;
        }
        $listing->setItemWidth($value);
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
