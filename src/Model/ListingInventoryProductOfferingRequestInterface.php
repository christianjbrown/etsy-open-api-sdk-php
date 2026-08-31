<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * One offering of one product in an `updateListingInventory` body.
 */
interface ListingInventoryProductOfferingRequestInterface
{
    public function getIsEnabled(): bool;

    public function getPrice(): float;

    public function getQuantity(): int;

    public function getReadinessStateId(): int;

    public function setIsEnabled(bool $value): self;

    public function setPrice(float $value): self;

    public function setQuantity(int $value): self;

    public function setReadinessStateId(int $value): self;
}
