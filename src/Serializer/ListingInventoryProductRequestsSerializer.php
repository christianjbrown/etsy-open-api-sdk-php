<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductRequestInterface;

use function array_values;
use function count;

final class ListingInventoryProductRequestsSerializer implements ListingInventoryProductRequestsSerializerInterface
{
    private ListingInventoryProductRequestSerializerInterface $listingInventoryProductRequestSerializer;

    public function __construct(ListingInventoryProductRequestSerializerInterface $listingInventoryProductRequestSerializer)
    {
        $this->listingInventoryProductRequestSerializer = $listingInventoryProductRequestSerializer;
    }

    /**
     * @param array<int, ListingInventoryProductRequestInterface> $listingInventoryProductRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $listingInventoryProductRequests): array
    {
        $data = [];
        $values = array_values($listingInventoryProductRequests);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $data[] = $this->listingInventoryProductRequestSerializer->serialize($values[$i]);
        }

        return $data;
    }
}
