<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingVariationImageRequestInterface;

use function array_values;
use function count;

final class ListingVariationImageRequestsSerializer implements ListingVariationImageRequestsSerializerInterface
{
    private ListingVariationImageRequestSerializerInterface $listingVariationImageRequestSerializer;

    public function __construct(ListingVariationImageRequestSerializerInterface $listingVariationImageRequestSerializer)
    {
        $this->listingVariationImageRequestSerializer = $listingVariationImageRequestSerializer;
    }

    /**
     * @param array<int, ListingVariationImageRequestInterface> $listingVariationImageRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $listingVariationImageRequests): array
    {
        $data = [];
        $values = array_values($listingVariationImageRequests);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $data[] = $this->listingVariationImageRequestSerializer->serialize($values[$i]);
        }

        return $data;
    }
}
