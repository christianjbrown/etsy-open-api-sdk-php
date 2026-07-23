<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingBuyerPriceInterface;

interface ListingBuyerPriceTransformerInterface
{
    public const string KEY_BASE_PRICE = 'base_price';
    public const string KEY_DISCOUNT_AMOUNT = 'discount_amount';
    public const string KEY_DISCOUNT_END_EPOCH = 'discount_end_epoch';
    public const string KEY_DISCOUNT_PERCENTAGE = 'discount_percentage';
    public const string KEY_DISCOUNT_START_EPOCH = 'discount_start_epoch';
    public const string KEY_DISCOUNTED_PRICE = 'discounted_price';
    public const string KEY_HAS_DISCOUNT = 'has_discount';
    public const string KEY_IS_FREE_SHIPPING = 'is_free_shipping';
    public const string KEY_ORIGINAL_PRICE = 'original_price';
    public const string KEY_SHIPPING_COST = 'shipping_cost';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingBuyerPriceInterface;
}
