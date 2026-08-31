<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductPropertyValueRequestInterface;

use function array_values;
use function count;

final class ListingInventoryProductPropertyValueRequestsSerializer implements ListingInventoryProductPropertyValueRequestsSerializerInterface
{
    private ListingInventoryProductPropertyValueRequestSerializerInterface $listingInventoryProductPropertyValueRequestSerializer;

    public function __construct(ListingInventoryProductPropertyValueRequestSerializerInterface $listingInventoryProductPropertyValueRequestSerializer)
    {
        $this->listingInventoryProductPropertyValueRequestSerializer = $listingInventoryProductPropertyValueRequestSerializer;
    }

    /**
     * @param array<int, ListingInventoryProductPropertyValueRequestInterface> $listingInventoryProductPropertyValueRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $listingInventoryProductPropertyValueRequests): array
    {
        $data = [];
        $values = array_values($listingInventoryProductPropertyValueRequests);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $data[] = $this->listingInventoryProductPropertyValueRequestSerializer->serialize($values[$i]);
        }

        return $data;
    }
}
