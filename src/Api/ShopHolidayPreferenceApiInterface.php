<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;

interface ShopHolidayPreferenceApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/holiday-preferences';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/holiday-preferences/%d';
    public const string KEY_IS_WORKING = 'is_working';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';

    /**
     * Reads all the holiday preferences for the shop.
     *
     * @return array<int, ShopHolidayPreferenceInterface>
     */
    public function getMultiple(bool $skipCache = false): array;

    /**
     * Sets whether the shop processes orders on a given holiday.
     */
    public function updateHolidayPreference(int $holidayId, bool $isWorking): ShopHolidayPreferenceInterface;
}
