<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;

interface ListingInventoryProductOfferingTransformerInterface
{
    public const string KEY_IS_DELETED = 'is_deleted';
    public const string KEY_IS_ENABLED = 'is_enabled';
    public const string KEY_OFFERING_ID = 'offering_id';
    public const string KEY_PRICE = 'price';
    public const string KEY_QUANTITY = 'quantity';
    public const string KEY_READINESS_STATE_ID = 'readiness_state_id';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingInventoryProductOfferingInterface;
}
