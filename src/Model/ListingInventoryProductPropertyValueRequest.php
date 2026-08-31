<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingInventoryProductPropertyValueRequest implements ListingInventoryProductPropertyValueRequestInterface
{
    private int $propertyId;
    private ?string $propertyName = null;
    private ?int $scaleId = null;

    /**
     * @var array<int, int>
     */
    private array $valueIds = [];

    /**
     * @var array<int, string>
     */
    private array $values = [];

    /**
     * @param int                $propertyId The property id
     * @param array<int, int>    $valueIds
     * @param array<int, string> $values
     */
    public function __construct(int $propertyId, array $valueIds, array $values)
    {
        $this->propertyId = $propertyId;
        $this->valueIds = $valueIds;
        $this->values = $values;
    }

    public function getPropertyId(): int
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

    public function setPropertyId(int $value): ListingInventoryProductPropertyValueRequestInterface
    {
        $this->propertyId = $value;

        return $this;
    }

    public function setPropertyName(?string $value): ListingInventoryProductPropertyValueRequestInterface
    {
        $this->propertyName = $value;

        return $this;
    }

    public function setScaleId(?int $value): ListingInventoryProductPropertyValueRequestInterface
    {
        $this->scaleId = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setValueIds(array $value): ListingInventoryProductPropertyValueRequestInterface
    {
        $this->valueIds = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setValues(array $value): ListingInventoryProductPropertyValueRequestInterface
    {
        $this->values = $value;

        return $this;
    }
}
