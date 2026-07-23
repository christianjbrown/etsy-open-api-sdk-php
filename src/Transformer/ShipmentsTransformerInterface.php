<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShipmentInterface;

interface ShipmentsTransformerInterface
{
    public const string ARRAY_NAME = 'shipment';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShipmentInterface>
     */
    public function transform(array $data): array;
}
