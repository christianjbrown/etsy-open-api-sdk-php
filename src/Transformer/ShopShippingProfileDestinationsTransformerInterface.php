<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;

interface ShopShippingProfileDestinationsTransformerInterface
{
    public const string ARRAY_NAME = 'shipping_profile_destination';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopShippingProfileDestinationInterface>
     */
    public function transform(array $data): array;
}
