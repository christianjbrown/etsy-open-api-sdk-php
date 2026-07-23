<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingVariationImageInterface;

interface ListingVariationImagesTransformerInterface
{
    public const string ARRAY_NAME = 'listingVariationImage';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingVariationImageInterface>
     */
    public function transform(array $data): array;
}
