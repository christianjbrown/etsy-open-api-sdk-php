<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShopShippingProfileDestinationInterface
{
    public function getDestinationCountryIso(): ?string;

    public function getDestinationRegion(): ?string;

    public function getMailClass(): ?string;

    public function getMaxDeliveryDays(): ?int;

    public function getMinDeliveryDays(): ?int;

    public function getOriginCountryIso(): ?string;

    public function getPrimaryCost(): ?MoneyInterface;

    public function getSecondaryCost(): ?MoneyInterface;

    public function getShippingCarrierId(): ?int;

    public function getShippingProfileDestinationId(): int;

    public function getShippingProfileId(): ?int;

    public function setDestinationCountryIso(?string $value): self;

    public function setDestinationRegion(?string $value): self;

    public function setMailClass(?string $value): self;

    public function setMaxDeliveryDays(?int $value): self;

    public function setMinDeliveryDays(?int $value): self;

    public function setOriginCountryIso(?string $value): self;

    public function setPrimaryCost(?MoneyInterface $value): self;

    public function setSecondaryCost(?MoneyInterface $value): self;

    public function setShippingCarrierId(?int $value): self;

    public function setShippingProfileDestinationId(int $value): self;

    public function setShippingProfileId(?int $value): self;
}
