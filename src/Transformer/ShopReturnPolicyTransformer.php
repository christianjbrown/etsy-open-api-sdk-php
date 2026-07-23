<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReturnPolicy;
use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;

use function is_bool;
use function is_int;
use function sprintf;

final class ShopReturnPolicyTransformer implements ShopReturnPolicyTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopReturnPolicyInterface
    {
        if (!isset($data[self::KEY_RETURN_POLICY_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_RETURN_POLICY_ID));
        }
        if (!is_int($data[self::KEY_RETURN_POLICY_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_RETURN_POLICY_ID));
        }
        $shopReturnPolicy = new ShopReturnPolicy($data[self::KEY_RETURN_POLICY_ID]);

        self::applyAcceptsExchanges($shopReturnPolicy, $data);
        self::applyAcceptsReturns($shopReturnPolicy, $data);
        self::applyReturnDeadline($shopReturnPolicy, $data);
        self::applyShopId($shopReturnPolicy, $data);

        return $shopReturnPolicy;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyAcceptsExchanges(ShopReturnPolicy $shopReturnPolicy, array $data): void
    {
        if (!isset($data[self::KEY_ACCEPTS_EXCHANGES])) {
            return;
        }
        if (!is_bool($data[self::KEY_ACCEPTS_EXCHANGES])) {
            return;
        }
        $shopReturnPolicy->setAcceptsExchanges($data[self::KEY_ACCEPTS_EXCHANGES]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyAcceptsReturns(ShopReturnPolicy $shopReturnPolicy, array $data): void
    {
        if (!isset($data[self::KEY_ACCEPTS_RETURNS])) {
            return;
        }
        if (!is_bool($data[self::KEY_ACCEPTS_RETURNS])) {
            return;
        }
        $shopReturnPolicy->setAcceptsReturns($data[self::KEY_ACCEPTS_RETURNS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReturnDeadline(ShopReturnPolicy $shopReturnPolicy, array $data): void
    {
        if (!isset($data[self::KEY_RETURN_DEADLINE])) {
            return;
        }
        if (!is_int($data[self::KEY_RETURN_DEADLINE])) {
            return;
        }
        $shopReturnPolicy->setReturnDeadline($data[self::KEY_RETURN_DEADLINE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopId(ShopReturnPolicy $shopReturnPolicy, array $data): void
    {
        if (!isset($data[self::KEY_SHOP_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHOP_ID])) {
            return;
        }
        $shopReturnPolicy->setShopId($data[self::KEY_SHOP_ID]);
    }
}
