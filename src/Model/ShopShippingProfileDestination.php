<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShopShippingProfileDestination implements ShopShippingProfileDestinationInterface
{
    private ?string $destinationCountryIso = null;
    private ?string $destinationRegion = null;
    private ?string $mailClass = null;
    private ?int $maxDeliveryDays = null;
    private ?int $minDeliveryDays = null;
    private ?string $originCountryIso = null;
    private ?MoneyInterface $primaryCost = null;
    private ?MoneyInterface $secondaryCost = null;
    private ?int $shippingCarrierId = null;
    private int $shippingProfileDestinationId;
    private ?int $shippingProfileId = null;

    public function __construct(int $shippingProfileDestinationId)
    {
        $this->shippingProfileDestinationId = $shippingProfileDestinationId;
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

    public function getOriginCountryIso(): ?string
    {
        return $this->originCountryIso;
    }

    public function getPrimaryCost(): ?MoneyInterface
    {
        return $this->primaryCost;
    }

    public function getSecondaryCost(): ?MoneyInterface
    {
        return $this->secondaryCost;
    }

    public function getShippingCarrierId(): ?int
    {
        return $this->shippingCarrierId;
    }

    public function getShippingProfileDestinationId(): int
    {
        return $this->shippingProfileDestinationId;
    }

    public function getShippingProfileId(): ?int
    {
        return $this->shippingProfileId;
    }

    public function setDestinationCountryIso(?string $value): ShopShippingProfileDestinationInterface
    {
        $this->destinationCountryIso = $value;

        return $this;
    }

    public function setDestinationRegion(?string $value): ShopShippingProfileDestinationInterface
    {
        $this->destinationRegion = $value;

        return $this;
    }

    public function setMailClass(?string $value): ShopShippingProfileDestinationInterface
    {
        $this->mailClass = $value;

        return $this;
    }

    public function setMaxDeliveryDays(?int $value): ShopShippingProfileDestinationInterface
    {
        $this->maxDeliveryDays = $value;

        return $this;
    }

    public function setMinDeliveryDays(?int $value): ShopShippingProfileDestinationInterface
    {
        $this->minDeliveryDays = $value;

        return $this;
    }

    public function setOriginCountryIso(?string $value): ShopShippingProfileDestinationInterface
    {
        $this->originCountryIso = $value;

        return $this;
    }

    public function setPrimaryCost(?MoneyInterface $value): ShopShippingProfileDestinationInterface
    {
        $this->primaryCost = $value;

        return $this;
    }

    public function setSecondaryCost(?MoneyInterface $value): ShopShippingProfileDestinationInterface
    {
        $this->secondaryCost = $value;

        return $this;
    }

    public function setShippingCarrierId(?int $value): ShopShippingProfileDestinationInterface
    {
        $this->shippingCarrierId = $value;

        return $this;
    }

    public function setShippingProfileDestinationId(int $value): ShopShippingProfileDestinationInterface
    {
        $this->shippingProfileDestinationId = $value;

        return $this;
    }

    public function setShippingProfileId(?int $value): ShopShippingProfileDestinationInterface
    {
        $this->shippingProfileId = $value;

        return $this;
    }
}
