<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `updateListingInventory` call. Etsy replaces the listing's whole inventory
 * with what is sent, so send every product the listing should keep.
 */
interface UpdateListingInventoryRequestInterface
{
    /**
     * @return array<int, int>
     */
    public function getPriceOnProperty(): array;

    /**
     * @return array<int, ListingInventoryProductRequestInterface>
     */
    public function getProducts(): array;

    /**
     * @return array<int, int>
     */
    public function getQuantityOnProperty(): array;

    /**
     * @return array<int, int>
     */
    public function getReadinessStateOnProperty(): array;

    /**
     * @return array<int, int>
     */
    public function getSkuOnProperty(): array;

    /**
     * @param array<int, int> $value
     */
    public function setPriceOnProperty(array $value): self;

    /**
     * @param array<int, ListingInventoryProductRequestInterface> $value
     */
    public function setProducts(array $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setQuantityOnProperty(array $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setReadinessStateOnProperty(array $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setSkuOnProperty(array $value): self;
}
