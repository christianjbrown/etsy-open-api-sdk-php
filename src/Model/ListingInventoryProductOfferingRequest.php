<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingInventoryProductOfferingRequest implements ListingInventoryProductOfferingRequestInterface
{
    private bool $isEnabled;
    private float $price;
    private int $quantity;
    private int $readinessStateId;

    public function __construct(float $price, int $quantity, bool $isEnabled, int $readinessStateId)
    {
        $this->price = $price;
        $this->quantity = $quantity;
        $this->isEnabled = $isEnabled;
        $this->readinessStateId = $readinessStateId;
    }

    public function getIsEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getReadinessStateId(): int
    {
        return $this->readinessStateId;
    }

    public function setIsEnabled(bool $value): ListingInventoryProductOfferingRequestInterface
    {
        $this->isEnabled = $value;

        return $this;
    }

    public function setPrice(float $value): ListingInventoryProductOfferingRequestInterface
    {
        $this->price = $value;

        return $this;
    }

    public function setQuantity(int $value): ListingInventoryProductOfferingRequestInterface
    {
        $this->quantity = $value;

        return $this;
    }

    public function setReadinessStateId(int $value): ListingInventoryProductOfferingRequestInterface
    {
        $this->readinessStateId = $value;

        return $this;
    }
}
