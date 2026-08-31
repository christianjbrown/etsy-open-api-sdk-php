<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class CreateShopShippingProfileRequest implements CreateShopShippingProfileRequestInterface
{
    private ?string $destinationCountryIso = null;
    private ?string $destinationRegion = null;
    private ?string $mailClass = null;
    private ?int $maxDeliveryDays = null;
    private ?int $maxProcessingTime = null;
    private ?int $minDeliveryDays = null;
    private ?int $minProcessingTime = null;
    private string $originCountryIso;
    private ?string $originPostalCode = null;
    private float $primaryCost;
    private ?string $processingTimeUnit = null;
    private float $secondaryCost;
    private ?int $shippingCarrierId = null;
    private string $title;

    public function __construct(string $title, string $originCountryIso, float $primaryCost, float $secondaryCost)
    {
        $this->title = $title;
        $this->originCountryIso = $originCountryIso;
        $this->primaryCost = $primaryCost;
        $this->secondaryCost = $secondaryCost;
    }

    public function getDestinationCountryIso(): ?string
    {
        return $this->destinationCountryIso;
    }

    public function getDestinationRegion(): ?string
    {
        return $this->destinationRegion;
    }

    public function getMailClass(): ?string
    {
        return $this->mailClass;
    }

    public function getMaxDeliveryDays(): ?int
    {
        return $this->maxDeliveryDays;
    }

    public function getMaxProcessingTime(): ?int
    {
        return $this->maxProcessingTime;
    }

    public function getMinDeliveryDays(): ?int
    {
        return $this->minDeliveryDays;
    }

    public function getMinProcessingTime(): ?int
    {
        return $this->minProcessingTime;
    }

    public function getOriginCountryIso(): string
    {
        return $this->originCountryIso;
    }

    public function getOriginPostalCode(): ?string
    {
        return $this->originPostalCode;
    }

    public function getPrimaryCost(): float
    {
        return $this->primaryCost;
    }

    public function getProcessingTimeUnit(): ?string
    {
        return $this->processingTimeUnit;
    }

    public function getSecondaryCost(): float
    {
        return $this->secondaryCost;
    }

    public function getShippingCarrierId(): ?int
    {
        return $this->shippingCarrierId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDestinationCountryIso(?string $value): CreateShopShippingProfileRequestInterface
    {
        $this->destinationCountryIso = $value;

        return $this;
    }

    public function setDestinationRegion(?string $value): CreateShopShippingProfileRequestInterface
    {
        $this->destinationRegion = $value;

        return $this;
    }

    public function setMailClass(?string $value): CreateShopShippingProfileRequestInterface
    {
        $this->mailClass = $value;

        return $this;
    }

    public function setMaxDeliveryDays(?int $value): CreateShopShippingProfileRequestInterface
    {
        $this->maxDeliveryDays = $value;

        return $this;
    }

    public function setMaxProcessingTime(?int $value): CreateShopShippingProfileRequestInterface
    {
        $this->maxProcessingTime = $value;

        return $this;
    }

    public function setMinDeliveryDays(?int $value): CreateShopShippingProfileRequestInterface
    {
        $this->minDeliveryDays = $value;

        return $this;
    }

    public function setMinProcessingTime(?int $value): CreateShopShippingProfileRequestInterface
    {
        $this->minProcessingTime = $value;

        return $this;
    }

    public function setOriginCountryIso(string $value): CreateShopShippingProfileRequestInterface
    {
        $this->originCountryIso = $value;

        return $this;
    }

    public function setOriginPostalCode(?string $value): CreateShopShippingProfileRequestInterface
    {
        $this->originPostalCode = $value;

        return $this;
    }

    public function setPrimaryCost(float $value): CreateShopShippingProfileRequestInterface
    {
        $this->primaryCost = $value;

        return $this;
    }

    public function setProcessingTimeUnit(?string $value): CreateShopShippingProfileRequestInterface
    {
        $this->processingTimeUnit = $value;

        return $this;
    }

    public function setSecondaryCost(float $value): CreateShopShippingProfileRequestInterface
    {
        $this->secondaryCost = $value;

        return $this;
    }

    public function setShippingCarrierId(?int $value): CreateShopShippingProfileRequestInterface
    {
        $this->shippingCarrierId = $value;

        return $this;
    }

    public function setTitle(string $value): CreateShopShippingProfileRequestInterface
    {
        $this->title = $value;

        return $this;
    }
}
