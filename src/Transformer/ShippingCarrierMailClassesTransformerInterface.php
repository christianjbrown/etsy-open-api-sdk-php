<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShippingCarrierMailClassInterface;

interface ShippingCarrierMailClassesTransformerInterface
{
    public const string ARRAY_NAME = 'shipping_carrier_mail_class';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShippingCarrierMailClassInterface>
     */
    public function transform(array $data): array;
}
