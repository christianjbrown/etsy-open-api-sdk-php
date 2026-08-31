<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of a `createShopShippingProfileUpgrade` call.
 */
interface CreateShopShippingProfileUpgradeRequestInterface
{
    public function getMailClass(): ?string;

    public function getMaxDeliveryDays(): ?int;

    public function getMinDeliveryDays(): ?int;

    public function getPrice(): float;

    public function getSecondaryPrice(): float;

    public function getShippingCarrierId(): ?int;

    public function getType(): int;

    public function getUpgradeName(): string;

    public function setMailClass(?string $value): self;

    public function setMaxDeliveryDays(?int $value): self;

    public function setMinDeliveryDays(?int $value): self;

    public function setPrice(float $value): self;

    public function setSecondaryPrice(float $value): self;

    public function setShippingCarrierId(?int $value): self;

    public function setType(int $value): self;

    public function setUpgradeName(string $value): self;
}
