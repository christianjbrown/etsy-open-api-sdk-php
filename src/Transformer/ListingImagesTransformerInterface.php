<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingImageInterface;

interface ListingImagesTransformerInterface
{
    public const string ARRAY_NAME = 'listingImage';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingImageInterface>
     */
    public function transform(array $data): array;
}
