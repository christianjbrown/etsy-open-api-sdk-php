<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingInventoryProductOffering implements ListingInventoryProductOfferingInterface
{
    private ?bool $isDeleted = null;
    private ?bool $isEnabled = null;
    private int $offeringId;
    private ?MoneyInterface $price = null;
    private ?int $quantity = null;
    private ?int $readinessStateId = null;

    public function __construct(int $offeringId)
    {
        $this->offeringId = $offeringId;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function getOfferingId(): int
    {
        return $this->offeringId;
    }

    public function getPrice(): ?MoneyInterface
    {
        return $this->price;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getReadinessStateId(): ?int
    {
        return $this->readinessStateId;
    }

    public function setIsDeleted(?bool $value): ListingInventoryProductOfferingInterface
    {
        $this->isDeleted = $value;

        return $this;
    }

    public function setIsEnabled(?bool $value): ListingInventoryProductOfferingInterface
    {
        $this->isEnabled = $value;

        return $this;
    }

    public function setOfferingId(int $value): ListingInventoryProductOfferingInterface
    {
        $this->offeringId = $value;

        return $this;
    }

    public function setPrice(?MoneyInterface $value): ListingInventoryProductOfferingInterface
    {
        $this->price = $value;

        return $this;
    }

    public function setQuantity(?int $value): ListingInventoryProductOfferingInterface
    {
        $this->quantity = $value;

        return $this;
    }

    public function setReadinessStateId(?int $value): ListingInventoryProductOfferingInterface
    {
        $this->readinessStateId = $value;

        return $this;
    }
}
