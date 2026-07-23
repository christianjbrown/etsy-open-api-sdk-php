<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingInventory implements ListingInventoryInterface
{
    private ?ListingInterface $listing = null;

    /**
     * @var array<int, int>
     */
    private array $priceOnProperty = [];

    /**
     * @var array<int, ListingInventoryProductInterface>
     */
    private array $products = [];

    /**
     * @var array<int, int>
     */
    private array $quantityOnProperty = [];

    /**
     * @var array<int, int>
     */
    private array $readinessStateOnProperty = [];

    /**
     * @var array<int, int>
     */
    private array $skuOnProperty = [];

    public function getListing(): ?ListingInterface
    {
        return $this->listing;
    }

    /**
     * @return array<int, int>
     */
    public function getPriceOnProperty(): array
    {
        return $this->priceOnProperty;
    }

    /**
     * @return array<int, ListingInventoryProductInterface>
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return array<int, int>
     */
    public function getQuantityOnProperty(): array
    {
        return $this->quantityOnProperty;
    }

    /**
     * @return array<int, int>
     */
    public function getReadinessStateOnProperty(): array
    {
        return $this->readinessStateOnProperty;
    }

    /**
     * @return array<int, int>
     */
    public function getSkuOnProperty(): array
    {
        return $this->skuOnProperty;
    }

    public function setListing(?ListingInterface $value): ListingInventoryInterface
    {
        $this->listing = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setPriceOnProperty(array $value): ListingInventoryInterface
    {
        $this->priceOnProperty = $value;

        return $this;
    }

    /**
     * @param array<int, ListingInventoryProductInterface> $value
     */
    public function setProducts(array $value): ListingInventoryInterface
    {
        $this->products = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setQuantityOnProperty(array $value): ListingInventoryInterface
    {
        $this->quantityOnProperty = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setReadinessStateOnProperty(array $value): ListingInventoryInterface
    {
        $this->readinessStateOnProperty = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setSkuOnProperty(array $value): ListingInventoryInterface
    {
        $this->skuOnProperty = $value;

        return $this;
    }
}
