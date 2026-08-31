<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductPropertyValueRequestInterface;

interface ListingInventoryProductPropertyValueRequestSerializerInterface
{
    public const string KEY_PROPERTY_ID = 'property_id';
    public const string KEY_PROPERTY_NAME = 'property_name';
    public const string KEY_SCALE_ID = 'scale_id';
    public const string KEY_VALUE_IDS = 'value_ids';
    public const string KEY_VALUES = 'values';

    /**
     * @return array<string, mixed>
     */
    public function serialize(ListingInventoryProductPropertyValueRequestInterface $listingInventoryProductPropertyValueRequest): array;
}
