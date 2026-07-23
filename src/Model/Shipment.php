<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Shipment implements ShipmentInterface
{
    private ?string $carrierName = null;
    private ?int $receiptShippingId = null;
    private ?int $shipmentNotificationTimestamp = null;
    private ?string $trackingCode = null;

    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }

    public function getReceiptShippingId(): ?int
    {
        return $this->receiptShippingId;
    }

    public function getShipmentNotificationTimestamp(): ?int
    {
        return $this->shipmentNotificationTimestamp;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    public function setCarrierName(?string $value): ShipmentInterface
    {
        $this->carrierName = $value;

        return $this;
    }

    public function setReceiptShippingId(?int $value): ShipmentInterface
    {
        $this->receiptShippingId = $value;

        return $this;
    }

    public function setShipmentNotificationTimestamp(?int $value): ShipmentInterface
    {
        $this->shipmentNotificationTimestamp = $value;

        return $this;
    }

    public function setTrackingCode(?string $value): ShipmentInterface
    {
        $this->trackingCode = $value;

        return $this;
    }
}
