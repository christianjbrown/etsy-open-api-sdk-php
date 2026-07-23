<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopSectionInterface;

interface ShopSectionsTransformerInterface
{
    public const string ARRAY_NAME = 'shop_section';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopSectionInterface>
     */
    public function transform(array $data): array;
}
