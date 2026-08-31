<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateListingInventoryRequest implements UpdateListingInventoryRequestInterface
{
    /**
     * @var array<int, int>
     */
    private array $priceOnProperty = [];

    /**
     * @var array<int, ListingInventoryProductRequestInterface>
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

    /**
     * @param array<int, ListingInventoryProductRequestInterface> $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * @return array<int, int>
     */
    public function getPriceOnProperty(): array
    {
        return $this->priceOnProperty;
    }

    /**
     * @return array<int, ListingInventoryProductRequestInterface>
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

    /**
     * @param array<int, int> $value
     */
    public function setPriceOnProperty(array $value): UpdateListingInventoryRequestInterface
    {
        $this->priceOnProperty = $value;

        return $this;
    }

    /**
     * @param array<int, ListingInventoryProductRequestInterface> $value
     */
    public function setProducts(array $value): UpdateListingInventoryRequestInterface
    {
        $this->products = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setQuantityOnProperty(array $value): UpdateListingInventoryRequestInterface
    {
        $this->quantityOnProperty = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setReadinessStateOnProperty(array $value): UpdateListingInventoryRequestInterface
    {
        $this->readinessStateOnProperty = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setSkuOnProperty(array $value): UpdateListingInventoryRequestInterface
    {
        $this->skuOnProperty = $value;

        return $this;
    }
}
