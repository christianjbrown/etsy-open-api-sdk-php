<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingVariationImageRequest implements ListingVariationImageRequestInterface
{
    private int $imageId;
    private int $propertyId;
    private int $valueId;

    public function __construct(int $propertyId, int $valueId, int $imageId)
    {
        $this->propertyId = $propertyId;
        $this->valueId = $valueId;
        $this->imageId = $imageId;
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }

    public function getPropertyId(): int
    {
        return $this->propertyId;
    }

    public function getValueId(): int
    {
        return $this->valueId;
    }

    public function setImageId(int $value): ListingVariationImageRequestInterface
    {
        $this->imageId = $value;

        return $this;
    }

    public function setPropertyId(int $value): ListingVariationImageRequestInterface
    {
        $this->propertyId = $value;

        return $this;
    }

    public function setValueId(int $value): ListingVariationImageRequestInterface
    {
        $this->valueId = $value;

        return $this;
    }
}
