<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingRequestInterface;

use function array_values;
use function count;

final class ListingInventoryProductOfferingRequestsSerializer implements ListingInventoryProductOfferingRequestsSerializerInterface
{
    private ListingInventoryProductOfferingRequestSerializerInterface $listingInventoryProductOfferingRequestSerializer;

    public function __construct(ListingInventoryProductOfferingRequestSerializerInterface $listingInventoryProductOfferingRequestSerializer)
    {
        $this->listingInventoryProductOfferingRequestSerializer = $listingInventoryProductOfferingRequestSerializer;
    }

    /**
     * @param array<int, ListingInventoryProductOfferingRequestInterface> $listingInventoryProductOfferingRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $listingInventoryProductOfferingRequests): array
    {
        $data = [];
        $values = array_values($listingInventoryProductOfferingRequests);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $data[] = $this->listingInventoryProductOfferingRequestSerializer->serialize($values[$i]);
        }

        return $data;
    }
}
