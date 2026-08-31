<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class CreateReceiptShipmentRequest implements CreateReceiptShipmentRequestInterface
{
    private ?string $carrierName = null;

    /**
     * @var array<int, ReceiptShipmentCustomsItemRequestInterface>
     */
    private array $customsData = [];
    private ?string $dimensionUnits = null;
    private ?float $dutyAmount = null;
    private ?string $dutyCurrency = null;
    private ?float $height = null;
    private ?string $incoterm = null;
    private ?float $length = null;
    private ?string $mailClass = null;
    private ?string $noteToBuyer = null;
    private ?string $revenueEligibility = null;
    private ?bool $sendBcc = null;
    private ?string $shipDate = null;
    private ?string $shipFromCountry = null;
    private ?float $shippingLabelCost = null;
    private ?string $shippingLabelCurrency = null;
    private ?string $shipToCountry = null;
    private ?string $trackingCode = null;
    private ?float $weight = null;
    private ?string $weightUnits = null;
    private ?float $width = null;

    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }

    /**
     * @return array<int, ReceiptShipmentCustomsItemRequestInterface>
     */
    public function getCustomsData(): array
    {
        return $this->customsData;
    }

    public function getDimensionUnits(): ?string
    {
        return $this->dimensionUnits;
    }

    public function getDutyAmount(): ?float
    {
        return $this->dutyAmount;
    }

    public function getDutyCurrency(): ?string
    {
        return $this->dutyCurrency;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function getIncoterm(): ?string
    {
        return $this->incoterm;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function getMailClass(): ?string
    {
        return $this->mailClass;
    }

    public function getNoteToBuyer(): ?string
    {
        return $this->noteToBuyer;
    }

    public function getRevenueEligibility(): ?string
    {
        return $this->revenueEligibility;
    }

    public function getSendBcc(): ?bool
    {
        return $this->sendBcc;
    }

    public function getShipDate(): ?string
    {
        return $this->shipDate;
    }

    public function getShipFromCountry(): ?string
    {
        return $this->shipFromCountry;
    }

    public function getShippingLabelCost(): ?float
    {
        return $this->shippingLabelCost;
    }

    public function getShippingLabelCurrency(): ?string
    {
        return $this->shippingLabelCurrency;
    }

    public function getShipToCountry(): ?string
    {
        return $this->shipToCountry;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function getWeightUnits(): ?string
    {
        return $this->weightUnits;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setCarrierName(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->carrierName = $value;

        return $this;
    }

    /**
     * @param array<int, ReceiptShipmentCustomsItemRequestInterface> $value
     */
    public function setCustomsData(array $value): CreateReceiptShipmentRequestInterface
    {
        $this->customsData = $value;

        return $this;
    }

    public function setDimensionUnits(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->dimensionUnits = $value;

        return $this;
    }

    public function setDutyAmount(?float $value): CreateReceiptShipmentRequestInterface
    {
        $this->dutyAmount = $value;

        return $this;
    }

    public function setDutyCurrency(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->dutyCurrency = $value;

        return $this;
    }

    public function setHeight(?float $value): CreateReceiptShipmentRequestInterface
    {
        $this->height = $value;

        return $this;
    }

    public function setIncoterm(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->incoterm = $value;

        return $this;
    }

    public function setLength(?float $value): CreateReceiptShipmentRequestInterface
    {
        $this->length = $value;

        return $this;
    }

    public function setMailClass(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->mailClass = $value;

        return $this;
    }

    public function setNoteToBuyer(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->noteToBuyer = $value;

        return $this;
    }

    public function setRevenueEligibility(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->revenueEligibility = $value;

        return $this;
    }

    public function setSendBcc(?bool $value): CreateReceiptShipmentRequestInterface
    {
        $this->sendBcc = $value;

        return $this;
    }

    public function setShipDate(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->shipDate = $value;

        return $this;
    }

    public function setShipFromCountry(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->shipFromCountry = $value;

        return $this;
    }

    public function setShippingLabelCost(?float $value): CreateReceiptShipmentRequestInterface
    {
        $this->shippingLabelCost = $value;

        return $this;
    }

    public function setShippingLabelCurrency(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->shippingLabelCurrency = $value;

        return $this;
    }

    public function setShipToCountry(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->shipToCountry = $value;

        return $this;
    }

    public function setTrackingCode(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->trackingCode = $value;

        return $this;
    }

    public function setWeight(?float $value): CreateReceiptShipmentRequestInterface
    {
        $this->weight = $value;

        return $this;
    }

    public function setWeightUnits(?string $value): CreateReceiptShipmentRequestInterface
    {
        $this->weightUnits = $value;

        return $this;
    }

    public function setWidth(?float $value): CreateReceiptShipmentRequestInterface
    {
        $this->width = $value;

        return $this;
    }
}
