<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingInventoryProduct implements ListingInventoryProductInterface
{
    private ?bool $isDeleted = null;

    /**
     * @var array<int, ListingInventoryProductOfferingInterface>
     */
    private array $offerings = [];
    private int $productId;

    /**
     * @var array<int, ListingPropertyValueInterface>
     */
    private array $propertyValues = [];
    private ?string $sku = null;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * @return array<int, ListingInventoryProductOfferingInterface>
     */
    public function getOfferings(): array
    {
        return $this->offerings;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return array<int, ListingPropertyValueInterface>
     */
    public function getPropertyValues(): array
    {
        return $this->propertyValues;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setIsDeleted(?bool $value): ListingInventoryProductInterface
    {
        $this->isDeleted = $value;

        return $this;
    }

    /**
     * @param array<int, ListingInventoryProductOfferingInterface> $value
     */
    public function setOfferings(array $value): ListingInventoryProductInterface
    {
        $this->offerings = $value;

        return $this;
    }

    public function setProductId(int $value): ListingInventoryProductInterface
    {
        $this->productId = $value;

        return $this;
    }

    /**
     * @param array<int, ListingPropertyValueInterface> $value
     */
    public function setPropertyValues(array $value): ListingInventoryProductInterface
    {
        $this->propertyValues = $value;

        return $this;
    }

    public function setSku(?string $value): ListingInventoryProductInterface
    {
        $this->sku = $value;

        return $this;
    }
}
