<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductRequestInterface;

interface ListingInventoryProductRequestsSerializerInterface
{
    /**
     * @param array<int, ListingInventoryProductRequestInterface> $listingInventoryProductRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $listingInventoryProductRequests): array;
}
