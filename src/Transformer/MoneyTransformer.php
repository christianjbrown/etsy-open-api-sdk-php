<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\Money;
use ChristianBrown\Etsy\Model\MoneyInterface;

use function is_int;
use function is_string;

final class MoneyTransformer implements MoneyTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): MoneyInterface
    {
        $money = new Money();

        self::applyAmount($money, $data);
        self::applyCurrencyCode($money, $data);
        self::applyDivisor($money, $data);

        return $money;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyAmount(Money $money, array $data): void
    {
        if (!isset($data[self::KEY_AMOUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_AMOUNT])) {
            return;
        }
        $money->setAmount($data[self::KEY_AMOUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCurrencyCode(Money $money, array $data): void
    {
        if (empty($data[self::KEY_CURRENCY_CODE])) {
            return;
        }
        if (!is_string($data[self::KEY_CURRENCY_CODE])) {
            return;
        }
        $money->setCurrencyCode($data[self::KEY_CURRENCY_CODE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDivisor(Money $money, array $data): void
    {
        if (!isset($data[self::KEY_DIVISOR])) {
            return;
        }
        if (!is_int($data[self::KEY_DIVISOR])) {
            return;
        }
        $money->setDivisor($data[self::KEY_DIVISOR]);
    }
}
