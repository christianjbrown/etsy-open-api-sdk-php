<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class CreateShopShippingProfileUpgradeRequest implements CreateShopShippingProfileUpgradeRequestInterface
{
    private ?string $mailClass = null;
    private ?int $maxDeliveryDays = null;
    private ?int $minDeliveryDays = null;
    private float $price;
    private float $secondaryPrice;
    private ?int $shippingCarrierId = null;
    private int $type;
    private string $upgradeName;

    public function __construct(int $type, string $upgradeName, float $price, float $secondaryPrice)
    {
        $this->type = $type;
        $this->upgradeName = $upgradeName;
        $this->price = $price;
        $this->secondaryPrice = $secondaryPrice;
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSecondaryPrice(): float
    {
        return $this->secondaryPrice;
    }

    public function getShippingCarrierId(): ?int
    {
        return $this->shippingCarrierId;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getUpgradeName(): string
    {
        return $this->upgradeName;
    }

    public function setMailClass(?string $value): CreateShopShippingProfileUpgradeRequestInterface
    {
        $this->mailClass = $value;

        return $this;
    }

    public function setMaxDeliveryDays(?int $value): CreateShopShippingProfileUpgradeRequestInterface
    {
        $this->maxDeliveryDays = $value;

        return $this;
    }

    public function setMinDeliveryDays(?int $value): CreateShopShippingProfileUpgradeRequestInterface
    {
        $this->minDeliveryDays = $value;

        return $this;
    }

    public function setPrice(float $value): CreateShopShippingProfileUpgradeRequestInterface
    {
        $this->price = $value;

        return $this;
    }

    public function setSecondaryPrice(float $value): CreateShopShippingProfileUpgradeRequestInterface
    {
        $this->secondaryPrice = $value;

        return $this;
    }

    public function setShippingCarrierId(?int $value): CreateShopShippingProfileUpgradeRequestInterface
    {
        $this->shippingCarrierId = $value;

        return $this;
    }

    public function setType(int $value): CreateShopShippingProfileUpgradeRequestInterface
    {
        $this->type = $value;

        return $this;
    }

    public function setUpgradeName(string $value): CreateShopShippingProfileUpgradeRequestInterface
    {
        $this->upgradeName = $value;

        return $this;
    }
}
