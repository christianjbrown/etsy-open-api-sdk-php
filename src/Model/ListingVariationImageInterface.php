<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingVariationImageInterface
{
    public function getImageId(): ?int;

    public function getPropertyId(): ?int;

    public function getValue(): ?string;

    public function getValueId(): ?int;

    public function setImageId(?int $value): self;

    public function setPropertyId(?int $value): self;

    public function setValue(?string $value): self;

    public function setValueId(?int $value): self;
}
