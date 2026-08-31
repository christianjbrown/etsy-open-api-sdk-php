<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateShopShippingProfileUpgradeRequest implements UpdateShopShippingProfileUpgradeRequestInterface
{
    private ?string $mailClass = null;
    private ?int $maxDeliveryDays = null;
    private ?int $minDeliveryDays = null;
    private ?float $price = null;
    private ?float $secondaryPrice = null;
    private ?int $shippingCarrierId = null;
    private ?int $type = null;
    private ?string $upgradeName = null;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getSecondaryPrice(): ?float
    {
        return $this->secondaryPrice;
    }

    public function getShippingCarrierId(): ?int
    {
        return $this->shippingCarrierId;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function getUpgradeName(): ?string
    {
        return $this->upgradeName;
    }

    public function setMailClass(?string $value): UpdateShopShippingProfileUpgradeRequestInterface
    {
        $this->mailClass = $value;

        return $this;
    }

    public function setMaxDeliveryDays(?int $value): UpdateShopShippingProfileUpgradeRequestInterface
    {
        $this->maxDeliveryDays = $value;

        return $this;
    }

    public function setMinDeliveryDays(?int $value): UpdateShopShippingProfileUpgradeRequestInterface
    {
        $this->minDeliveryDays = $value;

        return $this;
    }

    public function setPrice(?float $value): UpdateShopShippingProfileUpgradeRequestInterface
    {
        $this->price = $value;

        return $this;
    }

    public function setSecondaryPrice(?float $value): UpdateShopShippingProfileUpgradeRequestInterface
    {
        $this->secondaryPrice = $value;

        return $this;
    }

    public function setShippingCarrierId(?int $value): UpdateShopShippingProfileUpgradeRequestInterface
    {
        $this->shippingCarrierId = $value;

        return $this;
    }

    public function setType(?int $value): UpdateShopShippingProfileUpgradeRequestInterface
    {
        $this->type = $value;

        return $this;
    }

    public function setUpgradeName(?string $value): UpdateShopShippingProfileUpgradeRequestInterface
    {
        $this->upgradeName = $value;

        return $this;
    }
}
