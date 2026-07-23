<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;

interface ShopShippingProfileDestinationTransformerInterface
{
    public const string KEY_DESTINATION_COUNTRY_ISO = 'destination_country_iso';
    public const string KEY_DESTINATION_REGION = 'destination_region';
    public const string KEY_MAIL_CLASS = 'mail_class';
    public const string KEY_MAX_DELIVERY_DAYS = 'max_delivery_days';
    public const string KEY_MIN_DELIVERY_DAYS = 'min_delivery_days';
    public const string KEY_ORIGIN_COUNTRY_ISO = 'origin_country_iso';
    public const string KEY_PRIMARY_COST = 'primary_cost';
    public const string KEY_SECONDARY_COST = 'secondary_cost';
    public const string KEY_SHIPPING_CARRIER_ID = 'shipping_carrier_id';
    public const string KEY_SHIPPING_PROFILE_DESTINATION_ID = 'shipping_profile_destination_id';
    public const string KEY_SHIPPING_PROFILE_ID = 'shipping_profile_id';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopShippingProfileDestinationInterface;
}
