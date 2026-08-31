<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `updateShopShippingProfile` call.
 */
interface UpdateShopShippingProfileRequestInterface
{
    public function getMaxProcessingTime(): ?int;

    public function getMinProcessingTime(): ?int;

    public function getOriginCountryIso(): ?string;

    public function getOriginPostalCode(): ?string;

    public function getProcessingTimeUnit(): ?string;

    public function getTitle(): ?string;

    public function setMaxProcessingTime(?int $value): self;

    public function setMinProcessingTime(?int $value): self;

    public function setOriginCountryIso(?string $value): self;

    public function setOriginPostalCode(?string $value): self;

    public function setProcessingTimeUnit(?string $value): self;

    public function setTitle(?string $value): self;
}
