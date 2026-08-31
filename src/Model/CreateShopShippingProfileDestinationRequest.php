<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class CreateShopShippingProfileDestinationRequest implements CreateShopShippingProfileDestinationRequestInterface
{
    private ?string $destinationCountryIso = null;
    private ?string $destinationRegion = null;
    private ?string $mailClass = null;
    private ?int $maxDeliveryDays = null;
    private ?int $minDeliveryDays = null;
    private float $primaryCost;
    private float $secondaryCost;
    private ?int $shippingCarrierId = null;

    public function __construct(float $primaryCost, float $secondaryCost)
    {
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

    public function getMinDeliveryDays(): ?int
    {
        return $this->minDeliveryDays;
    }

    public function getPrimaryCost(): float
    {
        return $this->primaryCost;
    }

    public function getSecondaryCost(): float
    {
        return $this->secondaryCost;
    }

    public function getShippingCarrierId(): ?int
    {
        return $this->shippingCarrierId;
    }

    public function setDestinationCountryIso(?string $value): CreateShopShippingProfileDestinationRequestInterface
    {
        $this->destinationCountryIso = $value;

        return $this;
    }

    public function setDestinationRegion(?string $value): CreateShopShippingProfileDestinationRequestInterface
    {
        $this->destinationRegion = $value;

        return $this;
    }

    public function setMailClass(?string $value): CreateShopShippingProfileDestinationRequestInterface
    {
        $this->mailClass = $value;

        return $this;
    }

    public function setMaxDeliveryDays(?int $value): CreateShopShippingProfileDestinationRequestInterface
    {
        $this->maxDeliveryDays = $value;

        return $this;
    }

    public function setMinDeliveryDays(?int $value): CreateShopShippingProfileDestinationRequestInterface
    {
        $this->minDeliveryDays = $value;

        return $this;
    }

    public function setPrimaryCost(float $value): CreateShopShippingProfileDestinationRequestInterface
    {
        $this->primaryCost = $value;

        return $this;
    }

    public function setSecondaryCost(float $value): CreateShopShippingProfileDestinationRequestInterface
    {
        $this->secondaryCost = $value;

        return $this;
    }

    public function setShippingCarrierId(?int $value): CreateShopShippingProfileDestinationRequestInterface
    {
        $this->shippingCarrierId = $value;

        return $this;
    }
}
