<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingVariationImageRequestInterface;

interface ListingVariationImageRequestSerializerInterface
{
    public const string KEY_IMAGE_ID = 'image_id';
    public const string KEY_PROPERTY_ID = 'property_id';
    public const string KEY_VALUE_ID = 'value_id';

    /**
     * @return array<string, mixed>
     */
    public function serialize(ListingVariationImageRequestInterface $listingVariationImageRequest): array;
}
