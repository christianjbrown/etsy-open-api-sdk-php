<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\CreateShopShippingProfileRequestInterface;

interface CreateShopShippingProfileRequestSerializerInterface
{
    public const string KEY_DESTINATION_COUNTRY_ISO = 'destination_country_iso';
    public const string KEY_DESTINATION_REGION = 'destination_region';
    public const string KEY_MAIL_CLASS = 'mail_class';
    public const string KEY_MAX_DELIVERY_DAYS = 'max_delivery_days';
    public const string KEY_MAX_PROCESSING_TIME = 'max_processing_time';
    public const string KEY_MIN_DELIVERY_DAYS = 'min_delivery_days';
    public const string KEY_MIN_PROCESSING_TIME = 'min_processing_time';
    public const string KEY_ORIGIN_COUNTRY_ISO = 'origin_country_iso';
    public const string KEY_ORIGIN_POSTAL_CODE = 'origin_postal_code';
    public const string KEY_PRIMARY_COST = 'primary_cost';
    public const string KEY_PROCESSING_TIME_UNIT = 'processing_time_unit';
    public const string KEY_SECONDARY_COST = 'secondary_cost';
    public const string KEY_SHIPPING_CARRIER_ID = 'shipping_carrier_id';
    public const string KEY_TITLE = 'title';

    /**
     * @return array<string, string>
     */
    public function serialize(CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array;
}
