<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingVariationImage;
use ChristianBrown\Etsy\Model\ListingVariationImageInterface;

use function is_int;
use function is_string;

final class ListingVariationImageTransformer implements ListingVariationImageTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingVariationImageInterface
    {
        $variationImage = new ListingVariationImage();

        self::applyImageId($variationImage, $data);
        self::applyPropertyId($variationImage, $data);
        self::applyValue($variationImage, $data);
        self::applyValueId($variationImage, $data);

        return $variationImage;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyImageId(ListingVariationImage $variationImage, array $data): void
    {
        if (!isset($data[self::KEY_IMAGE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_IMAGE_ID])) {
            return;
        }
        $variationImage->setImageId($data[self::KEY_IMAGE_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPropertyId(ListingVariationImage $variationImage, array $data): void
    {
        if (!isset($data[self::KEY_PROPERTY_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_PROPERTY_ID])) {
            return;
        }
        $variationImage->setPropertyId($data[self::KEY_PROPERTY_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyValue(ListingVariationImage $variationImage, array $data): void
    {
        if (empty($data[self::KEY_VALUE])) {
            return;
        }
        if (!is_string($data[self::KEY_VALUE])) {
            return;
        }
        $variationImage->setValue($data[self::KEY_VALUE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyValueId(ListingVariationImage $variationImage, array $data): void
    {
        if (!isset($data[self::KEY_VALUE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_VALUE_ID])) {
            return;
        }
        $variationImage->setValueId($data[self::KEY_VALUE_ID]);
    }
}
