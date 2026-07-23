<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;

interface ShopHolidayPreferencesTransformerInterface
{
    public const string ARRAY_NAME = 'shop_holiday_preference';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopHolidayPreferenceInterface>
     */
    public function transform(array $data): array;
}
