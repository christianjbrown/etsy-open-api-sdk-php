<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShippingCarrierInterface;

interface ShippingCarrierTransformerInterface
{
    public const string KEY_DOMESTIC_CLASSES = 'domestic_classes';
    public const string KEY_INTERNATIONAL_CLASSES = 'international_classes';
    public const string KEY_NAME = 'name';
    public const string KEY_SHIPPING_CARRIER_ID = 'shipping_carrier_id';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShippingCarrierInterface;
}
