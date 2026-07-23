<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingInventoryProductInterface
{
    public function getIsDeleted(): ?bool;

    /**
     * @return array<int, ListingInventoryProductOfferingInterface>
     */
    public function getOfferings(): array;

    public function getProductId(): int;

    /**
     * @return array<int, ListingPropertyValueInterface>
     */
    public function getPropertyValues(): array;

    public function getSku(): ?string;

    public function setIsDeleted(?bool $value): self;

    /**
     * @param array<int, ListingInventoryProductOfferingInterface> $value
     */
    public function setOfferings(array $value): self;

    public function setProductId(int $value): self;

    /**
     * @param array<int, ListingPropertyValueInterface> $value
     */
    public function setPropertyValues(array $value): self;

    public function setSku(?string $value): self;
}
