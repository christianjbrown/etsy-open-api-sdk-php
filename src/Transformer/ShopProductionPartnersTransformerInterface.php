<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopProductionPartnerInterface;

interface ShopProductionPartnersTransformerInterface
{
    public const string ARRAY_NAME = 'shop_production_partner';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopProductionPartnerInterface>
     */
    public function transform(array $data): array;
}
