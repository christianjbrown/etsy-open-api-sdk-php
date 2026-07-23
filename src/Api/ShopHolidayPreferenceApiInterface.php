<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;

interface ShopHolidayPreferenceApiInterface extends ApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/holiday-preferences';

    /**
     * Reads all the holiday preferences for the shop.
     *
     * @return array<int, ShopHolidayPreferenceInterface>
     */
    public function getMultiple(bool $skipCache = false): array;
}
