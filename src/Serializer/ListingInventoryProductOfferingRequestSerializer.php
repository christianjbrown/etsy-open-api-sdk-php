<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingRequestInterface;

final class ListingInventoryProductOfferingRequestSerializer implements ListingInventoryProductOfferingRequestSerializerInterface
{
    /**
     * @return array<string, mixed>
     */
    public function serialize(ListingInventoryProductOfferingRequestInterface $listingInventoryProductOfferingRequest): array
    {
        $data = [];

        $data = self::applyIsEnabled($data, $listingInventoryProductOfferingRequest);
        $data = self::applyPrice($data, $listingInventoryProductOfferingRequest);
        $data = self::applyQuantity($data, $listingInventoryProductOfferingRequest);
        $data = self::applyReadinessStateId($data, $listingInventoryProductOfferingRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyIsEnabled(array $data, ListingInventoryProductOfferingRequestInterface $listingInventoryProductOfferingRequest): array
    {
        $data[self::KEY_IS_ENABLED] = $listingInventoryProductOfferingRequest->getIsEnabled();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyPrice(array $data, ListingInventoryProductOfferingRequestInterface $listingInventoryProductOfferingRequest): array
    {
        $data[self::KEY_PRICE] = $listingInventoryProductOfferingRequest->getPrice();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyQuantity(array $data, ListingInventoryProductOfferingRequestInterface $listingInventoryProductOfferingRequest): array
    {
        $data[self::KEY_QUANTITY] = $listingInventoryProductOfferingRequest->getQuantity();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyReadinessStateId(array $data, ListingInventoryProductOfferingRequestInterface $listingInventoryProductOfferingRequest): array
    {
        $data[self::KEY_READINESS_STATE_ID] = $listingInventoryProductOfferingRequest->getReadinessStateId();

        return $data;
    }
}
