<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;

interface ShopHolidayPreferenceTransformerInterface
{
    public const string KEY_COUNTRY_ISO = 'country_iso';
    public const string KEY_HOLIDAY_ID = 'holiday_id';
    public const string KEY_HOLIDAY_NAME = 'holiday_name';
    public const string KEY_IS_WORKING = 'is_working';
    public const string KEY_SHOP_ID = 'shop_id';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopHolidayPreferenceInterface;
}
