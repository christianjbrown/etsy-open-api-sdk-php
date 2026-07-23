<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingInventoryInterface
{
    public function getListing(): ?ListingInterface;

    /**
     * @return array<int, int>
     */
    public function getPriceOnProperty(): array;

    /**
     * @return array<int, ListingInventoryProductInterface>
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

    public function setListing(?ListingInterface $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setPriceOnProperty(array $value): self;

    /**
     * @param array<int, ListingInventoryProductInterface> $value
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
