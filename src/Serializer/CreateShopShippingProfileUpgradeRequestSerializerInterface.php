<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\CreateShopShippingProfileUpgradeRequestInterface;

interface CreateShopShippingProfileUpgradeRequestSerializerInterface
{
    public const string KEY_MAIL_CLASS = 'mail_class';
    public const string KEY_MAX_DELIVERY_DAYS = 'max_delivery_days';
    public const string KEY_MIN_DELIVERY_DAYS = 'min_delivery_days';
    public const string KEY_PRICE = 'price';
    public const string KEY_SECONDARY_PRICE = 'secondary_price';
    public const string KEY_SHIPPING_CARRIER_ID = 'shipping_carrier_id';
    public const string KEY_TYPE = 'type';
    public const string KEY_UPGRADE_NAME = 'upgrade_name';

    /**
     * @return array<string, string>
     */
    public function serialize(CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): array;
}
