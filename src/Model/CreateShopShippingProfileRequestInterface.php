<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of a `createShopShippingProfile` call.
 */
interface CreateShopShippingProfileRequestInterface
{
    public function getDestinationCountryIso(): ?string;

    public function getDestinationRegion(): ?string;

    public function getMailClass(): ?string;

    public function getMaxDeliveryDays(): ?int;

    public function getMaxProcessingTime(): ?int;

    public function getMinDeliveryDays(): ?int;

    public function getMinProcessingTime(): ?int;

    public function getOriginCountryIso(): string;

    public function getOriginPostalCode(): ?string;

    public function getPrimaryCost(): float;

    public function getProcessingTimeUnit(): ?string;

    public function getSecondaryCost(): float;

    public function getShippingCarrierId(): ?int;

    public function getTitle(): string;

    public function setDestinationCountryIso(?string $value): self;

    public function setDestinationRegion(?string $value): self;

    public function setMailClass(?string $value): self;

    public function setMaxDeliveryDays(?int $value): self;

    public function setMaxProcessingTime(?int $value): self;

    public function setMinDeliveryDays(?int $value): self;

    public function setMinProcessingTime(?int $value): self;

    public function setOriginCountryIso(string $value): self;

    public function setOriginPostalCode(?string $value): self;

    public function setPrimaryCost(float $value): self;

    public function setProcessingTimeUnit(?string $value): self;

    public function setSecondaryCost(float $value): self;

    public function setShippingCarrierId(?int $value): self;

    public function setTitle(string $value): self;
}
