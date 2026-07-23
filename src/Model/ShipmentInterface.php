<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShipmentInterface
{
    public function getCarrierName(): ?string;

    public function getReceiptShippingId(): ?int;

    public function getShipmentNotificationTimestamp(): ?int;

    public function getTrackingCode(): ?string;

    public function setCarrierName(?string $value): self;

    public function setReceiptShippingId(?int $value): self;

    public function setShipmentNotificationTimestamp(?int $value): self;

    public function setTrackingCode(?string $value): self;
}
