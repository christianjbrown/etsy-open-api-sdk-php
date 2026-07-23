<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;

interface ShopShippingProfilesTransformerInterface
{
    public const string ARRAY_NAME = 'shipping_profile';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopShippingProfileInterface>
     */
    public function transform(array $data): array;
}
