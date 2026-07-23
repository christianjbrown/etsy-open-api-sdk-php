<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingInventoryProductOfferingInterface
{
    public function getIsDeleted(): ?bool;

    public function getIsEnabled(): ?bool;

    public function getOfferingId(): int;

    public function getPrice(): ?MoneyInterface;

    public function getQuantity(): ?int;

    public function getReadinessStateId(): ?int;

    public function setIsDeleted(?bool $value): self;

    public function setIsEnabled(?bool $value): self;

    public function setOfferingId(int $value): self;

    public function setPrice(?MoneyInterface $value): self;

    public function setQuantity(?int $value): self;

    public function setReadinessStateId(?int $value): self;
}
