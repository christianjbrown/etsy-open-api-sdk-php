<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopSectionInterface;

interface ShopSectionTransformerInterface
{
    public const string KEY_ACTIVE_LISTING_COUNT = 'active_listing_count';
    public const string KEY_RANK = 'rank';
    public const string KEY_SHOP_SECTION_ID = 'shop_section_id';
    public const string KEY_TITLE = 'title';
    public const string KEY_USER_ID = 'user_id';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopSectionInterface;
}
