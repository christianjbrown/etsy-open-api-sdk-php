<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateShopShippingProfileRequestInterface;

interface UpdateShopShippingProfileRequestSerializerInterface
{
    public const string KEY_MAX_PROCESSING_TIME = 'max_processing_time';
    public const string KEY_MIN_PROCESSING_TIME = 'min_processing_time';
    public const string KEY_ORIGIN_COUNTRY_ISO = 'origin_country_iso';
    public const string KEY_ORIGIN_POSTAL_CODE = 'origin_postal_code';
    public const string KEY_PROCESSING_TIME_UNIT = 'processing_time_unit';
    public const string KEY_TITLE = 'title';

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateShopShippingProfileRequestInterface $updateShopShippingProfileRequest): array;
}
