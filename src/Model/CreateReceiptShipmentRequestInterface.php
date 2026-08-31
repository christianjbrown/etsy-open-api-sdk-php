<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of a `createReceiptShipment` call.
 */
interface CreateReceiptShipmentRequestInterface
{
    public function getCarrierName(): ?string;

    /**
     * @return array<int, ReceiptShipmentCustomsItemRequestInterface>
     */
    public function getCustomsData(): array;

    public function getDimensionUnits(): ?string;

    public function getDutyAmount(): ?float;

    public function getDutyCurrency(): ?string;

    public function getHeight(): ?float;

    public function getIncoterm(): ?string;

    public function getLength(): ?float;

    public function getMailClass(): ?string;

    public function getNoteToBuyer(): ?string;

    public function getRevenueEligibility(): ?string;

    public function getSendBcc(): ?bool;

    public function getShipDate(): ?string;

    public function getShipFromCountry(): ?string;

    public function getShippingLabelCost(): ?float;

    public function getShippingLabelCurrency(): ?string;

    public function getShipToCountry(): ?string;

    public function getTrackingCode(): ?string;

    public function getWeight(): ?float;

    public function getWeightUnits(): ?string;

    public function getWidth(): ?float;

    public function setCarrierName(?string $value): self;

    /**
     * @param array<int, ReceiptShipmentCustomsItemRequestInterface> $value
     */
    public function setCustomsData(array $value): self;

    public function setDimensionUnits(?string $value): self;

    public function setDutyAmount(?float $value): self;

    public function setDutyCurrency(?string $value): self;

    public function setHeight(?float $value): self;

    public function setIncoterm(?string $value): self;

    public function setLength(?float $value): self;

    public function setMailClass(?string $value): self;

    public function setNoteToBuyer(?string $value): self;

    public function setRevenueEligibility(?string $value): self;

    public function setSendBcc(?bool $value): self;

    public function setShipDate(?string $value): self;

    public function setShipFromCountry(?string $value): self;

    public function setShippingLabelCost(?float $value): self;

    public function setShippingLabelCurrency(?string $value): self;

    public function setShipToCountry(?string $value): self;

    public function setTrackingCode(?string $value): self;

    public function setWeight(?float $value): self;

    public function setWeightUnits(?string $value): self;

    public function setWidth(?float $value): self;
}
