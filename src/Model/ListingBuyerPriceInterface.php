<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingBuyerPriceInterface
{
    public function getBasePrice(): ?MoneyInterface;

    public function getDiscountAmount(): ?MoneyInterface;

    public function getDiscountedPrice(): ?MoneyInterface;

    public function getDiscountEndEpoch(): ?int;

    public function getDiscountPercentage(): ?int;

    public function getDiscountStartEpoch(): ?int;

    public function getHasDiscount(): ?bool;

    public function getIsFreeShipping(): ?bool;

    public function getOriginalPrice(): ?MoneyInterface;

    public function getShippingCost(): ?MoneyInterface;

    public function setBasePrice(?MoneyInterface $value): self;

    public function setDiscountAmount(?MoneyInterface $value): self;

    public function setDiscountedPrice(?MoneyInterface $value): self;

    public function setDiscountEndEpoch(?int $value): self;

    public function setDiscountPercentage(?int $value): self;

    public function setDiscountStartEpoch(?int $value): self;

    public function setHasDiscount(?bool $value): self;

    public function setIsFreeShipping(?bool $value): self;

    public function setOriginalPrice(?MoneyInterface $value): self;

    public function setShippingCost(?MoneyInterface $value): self;
}
