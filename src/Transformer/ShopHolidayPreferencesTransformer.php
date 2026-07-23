<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ShopHolidayPreferencesTransformer implements ShopHolidayPreferencesTransformerInterface
{
    private ShopHolidayPreferenceTransformerInterface $shopHolidayPreferenceTransformer;

    public function __construct(ShopHolidayPreferenceTransformerInterface $shopHolidayPreferenceTransformer)
    {
        $this->shopHolidayPreferenceTransformer = $shopHolidayPreferenceTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopHolidayPreferenceInterface>
     */
    public function transform(array $data): array
    {
        $shopHolidayPreferences = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $shopHolidayPreferenceData = $values[$i];
            if (!is_array($shopHolidayPreferenceData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $shopHolidayPreferences[] = $this->shopHolidayPreferenceTransformer->transform($shopHolidayPreferenceData);
        }

        return $shopHolidayPreferences;
    }
}
