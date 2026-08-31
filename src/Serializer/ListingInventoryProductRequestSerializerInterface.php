<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductRequestInterface;

interface ListingInventoryProductRequestSerializerInterface
{
    public const string KEY_OFFERINGS = 'offerings';
    public const string KEY_PROPERTY_VALUES = 'property_values';
    public const string KEY_SKU = 'sku';

    /**
     * @return array<string, mixed>
     */
    public function serialize(ListingInventoryProductRequestInterface $listingInventoryProductRequest): array;
}
