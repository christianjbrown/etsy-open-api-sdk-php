<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\Shipment;
use ChristianBrown\Etsy\Model\ShipmentInterface;

use function is_int;
use function is_string;

final class ShipmentTransformer implements ShipmentTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShipmentInterface
    {
        $shipment = new Shipment();

        self::applyCarrierName($shipment, $data);
        self::applyReceiptShippingId($shipment, $data);
        self::applyShipmentNotificationTimestamp($shipment, $data);
        self::applyTrackingCode($shipment, $data);

        return $shipment;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCarrierName(Shipment $shipment, array $data): void
    {
        if (empty($data[self::KEY_CARRIER_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_CARRIER_NAME])) {
            return;
        }
        $shipment->setCarrierName($data[self::KEY_CARRIER_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReceiptShippingId(Shipment $shipment, array $data): void
    {
        if (!isset($data[self::KEY_RECEIPT_SHIPPING_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_RECEIPT_SHIPPING_ID])) {
            return;
        }
        $shipment->setReceiptShippingId($data[self::KEY_RECEIPT_SHIPPING_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShipmentNotificationTimestamp(Shipment $shipment, array $data): void
    {
        if (!isset($data[self::KEY_SHIPMENT_NOTIFICATION_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPMENT_NOTIFICATION_TIMESTAMP])) {
            return;
        }
        $shipment->setShipmentNotificationTimestamp($data[self::KEY_SHIPMENT_NOTIFICATION_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTrackingCode(Shipment $shipment, array $data): void
    {
        if (empty($data[self::KEY_TRACKING_CODE])) {
            return;
        }
        if (!is_string($data[self::KEY_TRACKING_CODE])) {
            return;
        }
        $shipment->setTrackingCode($data[self::KEY_TRACKING_CODE]);
    }
}
