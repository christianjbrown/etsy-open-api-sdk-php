<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingVariationImageInterface;

interface ListingVariationImageTransformerInterface
{
    public const string KEY_IMAGE_ID = 'image_id';
    public const string KEY_PROPERTY_ID = 'property_id';
    public const string KEY_VALUE = 'value';
    public const string KEY_VALUE_ID = 'value_id';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingVariationImageInterface;
}
