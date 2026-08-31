<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingInventoryProductRequest implements ListingInventoryProductRequestInterface
{
    /**
     * @var array<int, ListingInventoryProductOfferingRequestInterface>
     */
    private array $offerings = [];

    /**
     * @var array<int, ListingInventoryProductPropertyValueRequestInterface>
     */
    private array $propertyValues = [];
    private ?string $sku = null;

    /**
     * @param array<int, ListingInventoryProductOfferingRequestInterface>      $offerings
     * @param array<int, ListingInventoryProductPropertyValueRequestInterface> $propertyValues
     */
    public function __construct(array $offerings, array $propertyValues)
    {
        $this->offerings = $offerings;
        $this->propertyValues = $propertyValues;
    }

    /**
     * @return array<int, ListingInventoryProductOfferingRequestInterface>
     */
    public function getOfferings(): array
    {
        return $this->offerings;
    }

    /**
     * @return array<int, ListingInventoryProductPropertyValueRequestInterface>
     */
    public function getPropertyValues(): array
    {
        return $this->propertyValues;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param array<int, ListingInventoryProductOfferingRequestInterface> $value
     */
    public function setOfferings(array $value): ListingInventoryProductRequestInterface
    {
        $this->offerings = $value;

        return $this;
    }

    /**
     * @param array<int, ListingInventoryProductPropertyValueRequestInterface> $value
     */
    public function setPropertyValues(array $value): ListingInventoryProductRequestInterface
    {
        $this->propertyValues = $value;

        return $this;
    }

    public function setSku(?string $value): ListingInventoryProductRequestInterface
    {
        $this->sku = $value;

        return $this;
    }
}
