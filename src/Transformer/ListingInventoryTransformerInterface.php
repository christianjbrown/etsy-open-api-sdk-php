<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingInventoryInterface;

interface ListingInventoryTransformerInterface
{
    public const string KEY_LISTING = 'listing';
    public const string KEY_PRICE_ON_PROPERTY = 'price_on_property';
    public const string KEY_PRODUCTS = 'products';
    public const string KEY_QUANTITY_ON_PROPERTY = 'quantity_on_property';
    public const string KEY_READINESS_STATE_ON_PROPERTY = 'readiness_state_on_property';
    public const string KEY_SKU_ON_PROPERTY = 'sku_on_property';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingInventoryInterface;
}
