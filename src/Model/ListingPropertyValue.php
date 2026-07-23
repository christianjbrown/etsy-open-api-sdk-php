<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingPropertyValue implements ListingPropertyValueInterface
{
    private ?int $propertyId = null;
    private ?string $propertyName = null;
    private ?int $scaleId = null;
    private ?string $scaleName = null;

    /**
     * @var array<int, int>
     */
    private array $valueIds = [];

    /**
     * @var array<int, string>
     */
    private array $values = [];

    public function getPropertyId(): ?int
    {
        return $this->propertyId;
    }

    public function getPropertyName(): ?string
    {
        return $this->propertyName;
    }

    public function getScaleId(): ?int
    {
        return $this->scaleId;
    }

    public function getScaleName(): ?string
    {
        return $this->scaleName;
    }

    /**
     * @return array<int, int>
     */
    public function getValueIds(): array
    {
        return $this->valueIds;
    }

    /**
     * @return array<int, string>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function setPropertyId(?int $value): ListingPropertyValueInterface
    {
        $this->propertyId = $value;

        return $this;
    }

    public function setPropertyName(?string $value): ListingPropertyValueInterface
    {
        $this->propertyName = $value;

        return $this;
    }

    public function setScaleId(?int $value): ListingPropertyValueInterface
    {
        $this->scaleId = $value;

        return $this;
    }

    public function setScaleName(?string $value): ListingPropertyValueInterface
    {
        $this->scaleName = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setValueIds(array $value): ListingPropertyValueInterface
    {
        $this->valueIds = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setValues(array $value): ListingPropertyValueInterface
    {
        $this->values = $value;

        return $this;
    }
}
