<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingBuyerPrice;
use ChristianBrown\Etsy\Model\ListingBuyerPriceInterface;

use function is_array;
use function is_bool;
use function is_int;

final class ListingBuyerPriceTransformer implements ListingBuyerPriceTransformerInterface
{
    private MoneyTransformerInterface $moneyTransformer;

    public function __construct(MoneyTransformerInterface $moneyTransformer)
    {
        $this->moneyTransformer = $moneyTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingBuyerPriceInterface
    {
        $buyerPrice = new ListingBuyerPrice();

        $this->applyBasePrice($buyerPrice, $data);
        $this->applyDiscountAmount($buyerPrice, $data);
        self::applyDiscountEndEpoch($buyerPrice, $data);
        self::applyDiscountPercentage($buyerPrice, $data);
        self::applyDiscountStartEpoch($buyerPrice, $data);
        $this->applyDiscountedPrice($buyerPrice, $data);
        self::applyHasDiscount($buyerPrice, $data);
        self::applyIsFreeShipping($buyerPrice, $data);
        $this->applyOriginalPrice($buyerPrice, $data);
        $this->applyShippingCost($buyerPrice, $data);

        return $buyerPrice;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyBasePrice(ListingBuyerPrice $buyerPrice, array $data): void
    {
        if (empty($data[self::KEY_BASE_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_BASE_PRICE])) {
            return;
        }
        $buyerPrice->setBasePrice($this->moneyTransformer->transform($data[self::KEY_BASE_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyDiscountAmount(ListingBuyerPrice $buyerPrice, array $data): void
    {
        if (empty($data[self::KEY_DISCOUNT_AMOUNT])) {
            return;
        }
        if (!is_array($data[self::KEY_DISCOUNT_AMOUNT])) {
            return;
        }
        $buyerPrice->setDiscountAmount($this->moneyTransformer->transform($data[self::KEY_DISCOUNT_AMOUNT]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyDiscountedPrice(ListingBuyerPrice $buyerPrice, array $data): void
    {
        if (empty($data[self::KEY_DISCOUNTED_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_DISCOUNTED_PRICE])) {
            return;
        }
        $buyerPrice->setDiscountedPrice($this->moneyTransformer->transform($data[self::KEY_DISCOUNTED_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDiscountEndEpoch(ListingBuyerPrice $buyerPrice, array $data): void
    {
        if (!isset($data[self::KEY_DISCOUNT_END_EPOCH])) {
            return;
        }
        if (!is_int($data[self::KEY_DISCOUNT_END_EPOCH])) {
            return;
        }
        $buyerPrice->setDiscountEndEpoch($data[self::KEY_DISCOUNT_END_EPOCH]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDiscountPercentage(ListingBuyerPrice $buyerPrice, array $data): void
    {
        if (!isset($data[self::KEY_DISCOUNT_PERCENTAGE])) {
            return;
        }
        if (!is_int($data[self::KEY_DISCOUNT_PERCENTAGE])) {
            return;
        }
        $buyerPrice->setDiscountPercentage($data[self::KEY_DISCOUNT_PERCENTAGE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDiscountStartEpoch(ListingBuyerPrice $buyerPrice, array $data): void
    {
        if (!isset($data[self::KEY_DISCOUNT_START_EPOCH])) {
            return;
        }
        if (!is_int($data[self::KEY_DISCOUNT_START_EPOCH])) {
            return;
        }
        $buyerPrice->setDiscountStartEpoch($data[self::KEY_DISCOUNT_START_EPOCH]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyHasDiscount(ListingBuyerPrice $buyerPrice, array $data): void
    {
        if (!isset($data[self::KEY_HAS_DISCOUNT])) {
            return;
        }
        if (!is_bool($data[self::KEY_HAS_DISCOUNT])) {
            return;
        }
        $buyerPrice->setHasDiscount($data[self::KEY_HAS_DISCOUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsFreeShipping(ListingBuyerPrice $buyerPrice, array $data): void
    {
        if (!isset($data[self::KEY_IS_FREE_SHIPPING])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_FREE_SHIPPING])) {
            return;
        }
        $buyerPrice->setIsFreeShipping($data[self::KEY_IS_FREE_SHIPPING]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyOriginalPrice(ListingBuyerPrice $buyerPrice, array $data): void
    {
        if (empty($data[self::KEY_ORIGINAL_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_ORIGINAL_PRICE])) {
            return;
        }
        $buyerPrice->setOriginalPrice($this->moneyTransformer->transform($data[self::KEY_ORIGINAL_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyShippingCost(ListingBuyerPrice $buyerPrice, array $data): void
    {
        if (empty($data[self::KEY_SHIPPING_COST])) {
            return;
        }
        if (!is_array($data[self::KEY_SHIPPING_COST])) {
            return;
        }
        $buyerPrice->setShippingCost($this->moneyTransformer->transform($data[self::KEY_SHIPPING_COST]));
    }
}
