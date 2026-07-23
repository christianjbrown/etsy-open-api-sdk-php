<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;

interface ShopShippingProfileTransformerInterface
{
    public const string KEY_DOMESTIC_HANDLING_FEE = 'domestic_handling_fee';
    public const string KEY_INTERNATIONAL_HANDLING_FEE = 'international_handling_fee';
    public const string KEY_IS_DELETED = 'is_deleted';
    public const string KEY_ORIGIN_COUNTRY_ISO = 'origin_country_iso';
    public const string KEY_ORIGIN_POSTAL_CODE = 'origin_postal_code';
    public const string KEY_PROFILE_TYPE = 'profile_type';
    public const string KEY_SHIPPING_PROFILE_DESTINATIONS = 'shipping_profile_destinations';
    public const string KEY_SHIPPING_PROFILE_ID = 'shipping_profile_id';
    public const string KEY_SHIPPING_PROFILE_UPGRADES = 'shipping_profile_upgrades';
    public const string KEY_TITLE = 'title';
    public const string KEY_USER_ID = 'user_id';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopShippingProfileInterface;
}
