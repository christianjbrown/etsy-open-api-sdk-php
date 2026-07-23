<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopSection;
use ChristianBrown\Etsy\Model\ShopSectionInterface;

use function is_int;
use function is_string;
use function sprintf;

final class ShopSectionTransformer implements ShopSectionTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopSectionInterface
    {
        if (!isset($data[self::KEY_SHOP_SECTION_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_SHOP_SECTION_ID));
        }
        if (!is_int($data[self::KEY_SHOP_SECTION_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_SHOP_SECTION_ID));
        }
        $shopSection = new ShopSection($data[self::KEY_SHOP_SECTION_ID]);

        self::applyActiveListingCount($shopSection, $data);
        self::applyRank($shopSection, $data);
        self::applyTitle($shopSection, $data);
        self::applyUserId($shopSection, $data);

        return $shopSection;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyActiveListingCount(ShopSection $shopSection, array $data): void
    {
        if (!isset($data[self::KEY_ACTIVE_LISTING_COUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_ACTIVE_LISTING_COUNT])) {
            return;
        }
        $shopSection->setActiveListingCount($data[self::KEY_ACTIVE_LISTING_COUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyRank(ShopSection $shopSection, array $data): void
    {
        if (!isset($data[self::KEY_RANK])) {
            return;
        }
        if (!is_int($data[self::KEY_RANK])) {
            return;
        }
        $shopSection->setRank($data[self::KEY_RANK]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTitle(ShopSection $shopSection, array $data): void
    {
        if (empty($data[self::KEY_TITLE])) {
            return;
        }
        if (!is_string($data[self::KEY_TITLE])) {
            return;
        }
        $shopSection->setTitle($data[self::KEY_TITLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUserId(ShopSection $shopSection, array $data): void
    {
        if (!isset($data[self::KEY_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_USER_ID])) {
            return;
        }
        $shopSection->setUserId($data[self::KEY_USER_ID]);
    }
}
