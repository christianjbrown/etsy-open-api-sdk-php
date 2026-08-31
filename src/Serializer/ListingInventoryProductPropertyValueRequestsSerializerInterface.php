<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductPropertyValueRequestInterface;

interface ListingInventoryProductPropertyValueRequestsSerializerInterface
{
    /**
     * @param array<int, ListingInventoryProductPropertyValueRequestInterface> $listingInventoryProductPropertyValueRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $listingInventoryProductPropertyValueRequests): array;
}
