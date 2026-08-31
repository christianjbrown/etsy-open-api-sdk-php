<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * One product in an `updateListingInventory` body.
 */
interface ListingInventoryProductRequestInterface
{
    /**
     * @return array<int, ListingInventoryProductOfferingRequestInterface>
     */
    public function getOfferings(): array;

    /**
     * @return array<int, ListingInventoryProductPropertyValueRequestInterface>
     */
    public function getPropertyValues(): array;

    public function getSku(): ?string;

    /**
     * @param array<int, ListingInventoryProductOfferingRequestInterface> $value
     */
    public function setOfferings(array $value): self;

    /**
     * @param array<int, ListingInventoryProductPropertyValueRequestInterface> $value
     */
    public function setPropertyValues(array $value): self;

    public function setSku(?string $value): self;
}
