<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShipmentInterface;

interface ShipmentTransformerInterface
{
    public const string KEY_CARRIER_NAME = 'carrier_name';
    public const string KEY_RECEIPT_SHIPPING_ID = 'receipt_shipping_id';
    public const string KEY_SHIPMENT_NOTIFICATION_TIMESTAMP = 'shipment_notification_timestamp';
    public const string KEY_TRACKING_CODE = 'tracking_code';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShipmentInterface;
}
