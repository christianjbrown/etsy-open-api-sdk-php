<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingBuyerPrice implements ListingBuyerPriceInterface
{
    private ?MoneyInterface $basePrice = null;
    private ?MoneyInterface $discountAmount = null;
    private ?MoneyInterface $discountedPrice = null;
    private ?int $discountEndEpoch = null;
    private ?int $discountPercentage = null;
    private ?int $discountStartEpoch = null;
    private ?bool $hasDiscount = null;
    private ?bool $isFreeShipping = null;
    private ?MoneyInterface $originalPrice = null;
    private ?MoneyInterface $shippingCost = null;

    public function getBasePrice(): ?MoneyInterface
    {
        return $this->basePrice;
    }

    public function getDiscountAmount(): ?MoneyInterface
    {
        return $this->discountAmount;
    }

    public function getDiscountedPrice(): ?MoneyInterface
    {
        return $this->discountedPrice;
    }

    public function getDiscountEndEpoch(): ?int
    {
        return $this->discountEndEpoch;
    }

    public function getDiscountPercentage(): ?int
    {
        return $this->discountPercentage;
    }

    public function getDiscountStartEpoch(): ?int
    {
        return $this->discountStartEpoch;
    }

    public function getHasDiscount(): ?bool
    {
        return $this->hasDiscount;
    }

    public function getIsFreeShipping(): ?bool
    {
        return $this->isFreeShipping;
    }

    public function getOriginalPrice(): ?MoneyInterface
    {
        return $this->originalPrice;
    }

    public function getShippingCost(): ?MoneyInterface
    {
        return $this->shippingCost;
    }

    public function setBasePrice(?MoneyInterface $value): ListingBuyerPriceInterface
    {
        $this->basePrice = $value;

        return $this;
    }

    public function setDiscountAmount(?MoneyInterface $value): ListingBuyerPriceInterface
    {
        $this->discountAmount = $value;

        return $this;
    }

    public function setDiscountedPrice(?MoneyInterface $value): ListingBuyerPriceInterface
    {
        $this->discountedPrice = $value;

        return $this;
    }

    public function setDiscountEndEpoch(?int $value): ListingBuyerPriceInterface
    {
        $this->discountEndEpoch = $value;

        return $this;
    }

    public function setDiscountPercentage(?int $value): ListingBuyerPriceInterface
    {
        $this->discountPercentage = $value;

        return $this;
    }

    public function setDiscountStartEpoch(?int $value): ListingBuyerPriceInterface
    {
        $this->discountStartEpoch = $value;

        return $this;
    }

    public function setHasDiscount(?bool $value): ListingBuyerPriceInterface
    {
        $this->hasDiscount = $value;

        return $this;
    }

    public function setIsFreeShipping(?bool $value): ListingBuyerPriceInterface
    {
        $this->isFreeShipping = $value;

        return $this;
    }

    public function setOriginalPrice(?MoneyInterface $value): ListingBuyerPriceInterface
    {
        $this->originalPrice = $value;

        return $this;
    }

    public function setShippingCost(?MoneyInterface $value): ListingBuyerPriceInterface
    {
        $this->shippingCost = $value;

        return $this;
    }
}
