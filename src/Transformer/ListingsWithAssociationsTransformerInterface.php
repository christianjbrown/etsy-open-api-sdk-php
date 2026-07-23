<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

interface ListingsWithAssociationsTransformerInterface
{
    public const string ARRAY_NAME = 'listingWithAssociations';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingWithAssociationsInterface>
     */
    public function transform(array $data): array;
}
