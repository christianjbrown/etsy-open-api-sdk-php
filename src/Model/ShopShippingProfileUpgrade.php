<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShopShippingProfileUpgrade implements ShopShippingProfileUpgradeInterface
{
    private ?string $language = null;
    private ?string $mailClass = null;
    private ?int $maxDeliveryDays = null;
    private ?int $minDeliveryDays = null;
    private ?MoneyInterface $price = null;
    private ?int $rank = null;
    private ?MoneyInterface $secondaryPrice = null;
    private ?int $shippingCarrierId = null;
    private ?int $shippingProfileId = null;
    private ?int $type = null;
    private int $upgradeId;
    private ?string $upgradeName = null;

    public function __construct(int $upgradeId)
    {
        $this->upgradeId = $upgradeId;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
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

    public function getPrice(): ?MoneyInterface
    {
        return $this->price;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function getSecondaryPrice(): ?MoneyInterface
    {
        return $this->secondaryPrice;
    }

    public function getShippingCarrierId(): ?int
    {
        return $this->shippingCarrierId;
    }

    public function getShippingProfileId(): ?int
    {
        return $this->shippingProfileId;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function getUpgradeId(): int
    {
        return $this->upgradeId;
    }

    public function getUpgradeName(): ?string
    {
        return $this->upgradeName;
    }

    public function setLanguage(?string $value): ShopShippingProfileUpgradeInterface
    {
        $this->language = $value;

        return $this;
    }

    public function setMailClass(?string $value): ShopShippingProfileUpgradeInterface
    {
        $this->mailClass = $value;

        return $this;
    }

    public function setMaxDeliveryDays(?int $value): ShopShippingProfileUpgradeInterface
    {
        $this->maxDeliveryDays = $value;

        return $this;
    }

    public function setMinDeliveryDays(?int $value): ShopShippingProfileUpgradeInterface
    {
        $this->minDeliveryDays = $value;

        return $this;
    }

    public function setPrice(?MoneyInterface $value): ShopShippingProfileUpgradeInterface
    {
        $this->price = $value;

        return $this;
    }

    public function setRank(?int $value): ShopShippingProfileUpgradeInterface
    {
        $this->rank = $value;

        return $this;
    }

    public function setSecondaryPrice(?MoneyInterface $value): ShopShippingProfileUpgradeInterface
    {
        $this->secondaryPrice = $value;

        return $this;
    }

    public function setShippingCarrierId(?int $value): ShopShippingProfileUpgradeInterface
    {
        $this->shippingCarrierId = $value;

        return $this;
    }

    public function setShippingProfileId(?int $value): ShopShippingProfileUpgradeInterface
    {
        $this->shippingProfileId = $value;

        return $this;
    }

    public function setType(?int $value): ShopShippingProfileUpgradeInterface
    {
        $this->type = $value;

        return $this;
    }

    public function setUpgradeId(int $value): ShopShippingProfileUpgradeInterface
    {
        $this->upgradeId = $value;

        return $this;
    }

    public function setUpgradeName(?string $value): ShopShippingProfileUpgradeInterface
    {
        $this->upgradeName = $value;

        return $this;
    }
}
