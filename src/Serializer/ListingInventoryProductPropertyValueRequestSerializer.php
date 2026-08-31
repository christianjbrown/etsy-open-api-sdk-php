<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductPropertyValueRequestInterface;

final class ListingInventoryProductPropertyValueRequestSerializer implements ListingInventoryProductPropertyValueRequestSerializerInterface
{
    /**
     * @return array<string, mixed>
     */
    public function serialize(ListingInventoryProductPropertyValueRequestInterface $listingInventoryProductPropertyValueRequest): array
    {
        $data = [];

        $data = self::applyPropertyId($data, $listingInventoryProductPropertyValueRequest);
        $data = self::applyPropertyName($data, $listingInventoryProductPropertyValueRequest);
        $data = self::applyScaleId($data, $listingInventoryProductPropertyValueRequest);
        $data = self::applyValueIds($data, $listingInventoryProductPropertyValueRequest);
        $data = self::applyValues($data, $listingInventoryProductPropertyValueRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyPropertyId(array $data, ListingInventoryProductPropertyValueRequestInterface $listingInventoryProductPropertyValueRequest): array
    {
        $data[self::KEY_PROPERTY_ID] = $listingInventoryProductPropertyValueRequest->getPropertyId();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyPropertyName(array $data, ListingInventoryProductPropertyValueRequestInterface $listingInventoryProductPropertyValueRequest): array
    {
        $value = $listingInventoryProductPropertyValueRequest->getPropertyName();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_PROPERTY_NAME] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyScaleId(array $data, ListingInventoryProductPropertyValueRequestInterface $listingInventoryProductPropertyValueRequest): array
    {
        $value = $listingInventoryProductPropertyValueRequest->getScaleId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SCALE_ID] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyValueIds(array $data, ListingInventoryProductPropertyValueRequestInterface $listingInventoryProductPropertyValueRequest): array
    {
        $data[self::KEY_VALUE_IDS] = $listingInventoryProductPropertyValueRequest->getValueIds();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyValues(array $data, ListingInventoryProductPropertyValueRequestInterface $listingInventoryProductPropertyValueRequest): array
    {
        $data[self::KEY_VALUES] = $listingInventoryProductPropertyValueRequest->getValues();

        return $data;
    }
}
