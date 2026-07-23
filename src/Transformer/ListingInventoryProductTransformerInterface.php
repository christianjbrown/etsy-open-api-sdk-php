<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingInventoryProductInterface;

interface ListingInventoryProductTransformerInterface
{
    public const string KEY_IS_DELETED = 'is_deleted';
    public const string KEY_OFFERINGS = 'offerings';
    public const string KEY_PRODUCT_ID = 'product_id';
    public const string KEY_PROPERTY_VALUES = 'property_values';
    public const string KEY_SKU = 'sku';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingInventoryProductInterface;
}
