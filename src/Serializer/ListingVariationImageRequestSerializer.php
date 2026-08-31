<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingVariationImageRequestInterface;

final class ListingVariationImageRequestSerializer implements ListingVariationImageRequestSerializerInterface
{
    /**
     * @return array<string, mixed>
     */
    public function serialize(ListingVariationImageRequestInterface $listingVariationImageRequest): array
    {
        $data = [];

        $data = self::applyImageId($data, $listingVariationImageRequest);
        $data = self::applyPropertyId($data, $listingVariationImageRequest);
        $data = self::applyValueId($data, $listingVariationImageRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyImageId(array $data, ListingVariationImageRequestInterface $listingVariationImageRequest): array
    {
        $data[self::KEY_IMAGE_ID] = $listingVariationImageRequest->getImageId();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyPropertyId(array $data, ListingVariationImageRequestInterface $listingVariationImageRequest): array
    {
        $data[self::KEY_PROPERTY_ID] = $listingVariationImageRequest->getPropertyId();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyValueId(array $data, ListingVariationImageRequestInterface $listingVariationImageRequest): array
    {
        $data[self::KEY_VALUE_ID] = $listingVariationImageRequest->getValueId();

        return $data;
    }
}
