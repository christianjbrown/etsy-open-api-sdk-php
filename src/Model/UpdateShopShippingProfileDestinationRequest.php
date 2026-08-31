<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateShopShippingProfileDestinationRequest implements UpdateShopShippingProfileDestinationRequestInterface
{
    private ?string $destinationCountryIso = null;
    private ?string $destinationRegion = null;
    private ?string $mailClass = null;
    private ?int $maxDeliveryDays = null;
    private ?int $minDeliveryDays = null;
    private ?float $primaryCost = null;
    private ?float $secondaryCost = null;
    private ?int $shippingCarrierId = null;

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

    public function getPrimaryCost(): ?float
    {
        return $this->primaryCost;
    }

    public function getSecondaryCost(): ?float
    {
        return $this->secondaryCost;
    }

    public function getShippingCarrierId(): ?int
    {
        return $this->shippingCarrierId;
    }

    public function setDestinationCountryIso(?string $value): UpdateShopShippingProfileDestinationRequestInterface
    {
        $this->destinationCountryIso = $value;

        return $this;
    }

    public function setDestinationRegion(?string $value): UpdateShopShippingProfileDestinationRequestInterface
    {
        $this->destinationRegion = $value;

        return $this;
    }

    public function setMailClass(?string $value): UpdateShopShippingProfileDestinationRequestInterface
    {
        $this->mailClass = $value;

        return $this;
    }

    public function setMaxDeliveryDays(?int $value): UpdateShopShippingProfileDestinationRequestInterface
    {
        $this->maxDeliveryDays = $value;

        return $this;
    }

    public function setMinDeliveryDays(?int $value): UpdateShopShippingProfileDestinationRequestInterface
    {
        $this->minDeliveryDays = $value;

        return $this;
    }

    public function setPrimaryCost(?float $value): UpdateShopShippingProfileDestinationRequestInterface
    {
        $this->primaryCost = $value;

        return $this;
    }

    public function setSecondaryCost(?float $value): UpdateShopShippingProfileDestinationRequestInterface
    {
        $this->secondaryCost = $value;

        return $this;
    }

    public function setShippingCarrierId(?int $value): UpdateShopShippingProfileDestinationRequestInterface
    {
        $this->shippingCarrierId = $value;

        return $this;
    }
}
