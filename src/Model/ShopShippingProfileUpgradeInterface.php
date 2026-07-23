<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShopShippingProfileUpgradeInterface
{
    public function getLanguage(): ?string;

    public function getMailClass(): ?string;

    public function getMaxDeliveryDays(): ?int;

    public function getMinDeliveryDays(): ?int;

    public function getPrice(): ?MoneyInterface;

    public function getRank(): ?int;

    public function getSecondaryPrice(): ?MoneyInterface;

    public function getShippingCarrierId(): ?int;

    public function getShippingProfileId(): ?int;

    public function getType(): ?int;

    public function getUpgradeId(): int;

    public function getUpgradeName(): ?string;

    public function setLanguage(?string $value): self;

    public function setMailClass(?string $value): self;

    public function setMaxDeliveryDays(?int $value): self;

    public function setMinDeliveryDays(?int $value): self;

    public function setPrice(?MoneyInterface $value): self;

    public function setRank(?int $value): self;

    public function setSecondaryPrice(?MoneyInterface $value): self;

    public function setShippingCarrierId(?int $value): self;

    public function setShippingProfileId(?int $value): self;

    public function setType(?int $value): self;

    public function setUpgradeId(int $value): self;

    public function setUpgradeName(?string $value): self;
}
