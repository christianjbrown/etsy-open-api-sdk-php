<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingRequestInterface;

interface ListingInventoryProductOfferingRequestSerializerInterface
{
    public const string KEY_IS_ENABLED = 'is_enabled';
    public const string KEY_PRICE = 'price';
    public const string KEY_QUANTITY = 'quantity';
    public const string KEY_READINESS_STATE_ID = 'readiness_state_id';

    /**
     * @return array<string, mixed>
     */
    public function serialize(ListingInventoryProductOfferingRequestInterface $listingInventoryProductOfferingRequest): array;
}
