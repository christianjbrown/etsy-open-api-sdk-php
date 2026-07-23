<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;

interface ListingInventoryProductOfferingsTransformerInterface
{
    public const string ARRAY_NAME = 'listingInventoryProductOffering';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingInventoryProductOfferingInterface>
     */
    public function transform(array $data): array;
}
