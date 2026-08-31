<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingVariationImageRequestInterface;

interface ListingVariationImageRequestsSerializerInterface
{
    /**
     * @param array<int, ListingVariationImageRequestInterface> $listingVariationImageRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $listingVariationImageRequests): array;
}
