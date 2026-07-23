<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopHolidayPreference;
use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;

use function is_bool;
use function is_int;
use function is_string;

final class ShopHolidayPreferenceTransformer implements ShopHolidayPreferenceTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopHolidayPreferenceInterface
    {
        $shopHolidayPreference = new ShopHolidayPreference();

        self::applyCountryIso($shopHolidayPreference, $data);
        self::applyHolidayId($shopHolidayPreference, $data);
        self::applyHolidayName($shopHolidayPreference, $data);
        self::applyIsWorking($shopHolidayPreference, $data);
        self::applyShopId($shopHolidayPreference, $data);

        return $shopHolidayPreference;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCountryIso(ShopHolidayPreference $shopHolidayPreference, array $data): void
    {
        if (empty($data[self::KEY_COUNTRY_ISO])) {
            return;
        }
        if (!is_string($data[self::KEY_COUNTRY_ISO])) {
            return;
        }
        $shopHolidayPreference->setCountryIso($data[self::KEY_COUNTRY_ISO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyHolidayId(ShopHolidayPreference $shopHolidayPreference, array $data): void
    {
        if (!isset($data[self::KEY_HOLIDAY_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_HOLIDAY_ID])) {
            return;
        }
        $shopHolidayPreference->setHolidayId($data[self::KEY_HOLIDAY_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyHolidayName(ShopHolidayPreference $shopHolidayPreference, array $data): void
    {
        if (empty($data[self::KEY_HOLIDAY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_HOLIDAY_NAME])) {
            return;
        }
        $shopHolidayPreference->setHolidayName($data[self::KEY_HOLIDAY_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsWorking(ShopHolidayPreference $shopHolidayPreference, array $data): void
    {
        if (!isset($data[self::KEY_IS_WORKING])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_WORKING])) {
            return;
        }
        $shopHolidayPreference->setIsWorking($data[self::KEY_IS_WORKING]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopId(ShopHolidayPreference $shopHolidayPreference, array $data): void
    {
        if (!isset($data[self::KEY_SHOP_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHOP_ID])) {
            return;
        }
        $shopHolidayPreference->setShopId($data[self::KEY_SHOP_ID]);
    }
}
