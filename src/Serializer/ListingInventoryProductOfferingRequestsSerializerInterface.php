<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingRequestInterface;

interface ListingInventoryProductOfferingRequestsSerializerInterface
{
    /**
     * @param array<int, ListingInventoryProductOfferingRequestInterface> $listingInventoryProductOfferingRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $listingInventoryProductOfferingRequests): array;
}
