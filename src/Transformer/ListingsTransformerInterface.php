<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingInterface;

interface ListingsTransformerInterface
{
    public const string ARRAY_NAME = 'listing';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingInterface>
     */
    public function transform(array $data): array;
}
