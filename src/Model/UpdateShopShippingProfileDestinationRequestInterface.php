<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `updateShopShippingProfileDestination` call.
 */
interface UpdateShopShippingProfileDestinationRequestInterface
{
    public function getDestinationCountryIso(): ?string;

    public function getDestinationRegion(): ?string;

    public function getMailClass(): ?string;

    public function getMaxDeliveryDays(): ?int;

    public function getMinDeliveryDays(): ?int;

    public function getPrimaryCost(): ?float;

    public function getSecondaryCost(): ?float;

    public function getShippingCarrierId(): ?int;

    public function setDestinationCountryIso(?string $value): self;

    public function setDestinationRegion(?string $value): self;

    public function setMailClass(?string $value): self;

    public function setMaxDeliveryDays(?int $value): self;

    public function setMinDeliveryDays(?int $value): self;

    public function setPrimaryCost(?float $value): self;

    public function setSecondaryCost(?float $value): self;

    public function setShippingCarrierId(?int $value): self;
}
