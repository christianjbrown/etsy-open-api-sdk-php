<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;

interface ShopShippingProfileUpgradesTransformerInterface
{
    public const string ARRAY_NAME = 'shipping_profile_upgrade';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopShippingProfileUpgradeInterface>
     */
    public function transform(array $data): array;
}
