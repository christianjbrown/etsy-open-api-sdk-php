<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingPropertyValueInterface;

interface ListingPropertyValueTransformerInterface
{
    public const string KEY_PROPERTY_ID = 'property_id';
    public const string KEY_PROPERTY_NAME = 'property_name';
    public const string KEY_SCALE_ID = 'scale_id';
    public const string KEY_SCALE_NAME = 'scale_name';
    public const string KEY_VALUE_IDS = 'value_ids';
    public const string KEY_VALUES = 'values';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingPropertyValueInterface;
}
