<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingFileInterface;

interface ListingFilesTransformerInterface
{
    public const string ARRAY_NAME = 'listingFile';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingFileInterface>
     */
    public function transform(array $data): array;
}
