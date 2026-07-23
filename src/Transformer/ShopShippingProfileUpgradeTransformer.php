<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgrade;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;

use function is_array;
use function is_int;
use function is_string;
use function sprintf;

final class ShopShippingProfileUpgradeTransformer implements ShopShippingProfileUpgradeTransformerInterface
{
    private MoneyTransformerInterface $moneyTransformer;

    public function __construct(MoneyTransformerInterface $moneyTransformer)
    {
        $this->moneyTransformer = $moneyTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopShippingProfileUpgradeInterface
    {
        if (!isset($data[self::KEY_UPGRADE_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_UPGRADE_ID));
        }
        if (!is_int($data[self::KEY_UPGRADE_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_UPGRADE_ID));
        }
        $upgrade = new ShopShippingProfileUpgrade($data[self::KEY_UPGRADE_ID]);

        self::applyLanguage($upgrade, $data);
        self::applyMailClass($upgrade, $data);
        self::applyMaxDeliveryDays($upgrade, $data);
        self::applyMinDeliveryDays($upgrade, $data);
        self::applyRank($upgrade, $data);
        self::applyShippingCarrierId($upgrade, $data);
        self::applyShippingProfileId($upgrade, $data);
        self::applyType($upgrade, $data);
        self::applyUpgradeName($upgrade, $data);
        $this->applyPrice($upgrade, $data);
        $this->applySecondaryPrice($upgrade, $data);

        return $upgrade;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLanguage(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (empty($data[self::KEY_LANGUAGE])) {
            return;
        }
        if (!is_string($data[self::KEY_LANGUAGE])) {
            return;
        }
        $upgrade->setLanguage($data[self::KEY_LANGUAGE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMailClass(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (empty($data[self::KEY_MAIL_CLASS])) {
            return;
        }
        if (!is_string($data[self::KEY_MAIL_CLASS])) {
            return;
        }
        $upgrade->setMailClass($data[self::KEY_MAIL_CLASS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMaxDeliveryDays(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (!isset($data[self::KEY_MAX_DELIVERY_DAYS])) {
            return;
        }
        if (!is_int($data[self::KEY_MAX_DELIVERY_DAYS])) {
            return;
        }
        $upgrade->setMaxDeliveryDays($data[self::KEY_MAX_DELIVERY_DAYS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMinDeliveryDays(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (!isset($data[self::KEY_MIN_DELIVERY_DAYS])) {
            return;
        }
        if (!is_int($data[self::KEY_MIN_DELIVERY_DAYS])) {
            return;
        }
        $upgrade->setMinDeliveryDays($data[self::KEY_MIN_DELIVERY_DAYS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPrice(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (empty($data[self::KEY_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_PRICE])) {
            return;
        }
        $upgrade->setPrice($this->moneyTransformer->transform($data[self::KEY_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyRank(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (!isset($data[self::KEY_RANK])) {
            return;
        }
        if (!is_int($data[self::KEY_RANK])) {
            return;
        }
        $upgrade->setRank($data[self::KEY_RANK]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applySecondaryPrice(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (empty($data[self::KEY_SECONDARY_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_SECONDARY_PRICE])) {
            return;
        }
        $upgrade->setSecondaryPrice($this->moneyTransformer->transform($data[self::KEY_SECONDARY_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingCarrierId(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (!isset($data[self::KEY_SHIPPING_CARRIER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPPING_CARRIER_ID])) {
            return;
        }
        $upgrade->setShippingCarrierId($data[self::KEY_SHIPPING_CARRIER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingProfileId(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (!isset($data[self::KEY_SHIPPING_PROFILE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPPING_PROFILE_ID])) {
            return;
        }
        $upgrade->setShippingProfileId($data[self::KEY_SHIPPING_PROFILE_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyType(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (!isset($data[self::KEY_TYPE])) {
            return;
        }
        if (!is_int($data[self::KEY_TYPE])) {
            return;
        }
        $upgrade->setType($data[self::KEY_TYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpgradeName(ShopShippingProfileUpgrade $upgrade, array $data): void
    {
        if (empty($data[self::KEY_UPGRADE_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_UPGRADE_NAME])) {
            return;
        }
        $upgrade->setUpgradeName($data[self::KEY_UPGRADE_NAME]);
    }
}
