<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingVariationImage implements ListingVariationImageInterface
{
    private ?int $imageId = null;
    private ?int $propertyId = null;
    private ?string $value = null;
    private ?int $valueId = null;

    public function getImageId(): ?int
    {
        return $this->imageId;
    }

    public function getPropertyId(): ?int
    {
        return $this->propertyId;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getValueId(): ?int
    {
        return $this->valueId;
    }

    public function setImageId(?int $value): ListingVariationImageInterface
    {
        $this->imageId = $value;

        return $this;
    }

    public function setPropertyId(?int $value): ListingVariationImageInterface
    {
        $this->propertyId = $value;

        return $this;
    }

    public function setValue(?string $value): ListingVariationImageInterface
    {
        $this->value = $value;

        return $this;
    }

    public function setValueId(?int $value): ListingVariationImageInterface
    {
        $this->valueId = $value;

        return $this;
    }
}
