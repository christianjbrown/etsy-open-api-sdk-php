<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateListingInventoryRequestInterface;

final class UpdateListingInventoryRequestSerializer implements UpdateListingInventoryRequestSerializerInterface
{
    private ListingInventoryProductRequestsSerializerInterface $listingInventoryProductRequestsSerializer;

    public function __construct(ListingInventoryProductRequestsSerializerInterface $listingInventoryProductRequestsSerializer)
    {
        $this->listingInventoryProductRequestsSerializer = $listingInventoryProductRequestsSerializer;
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(UpdateListingInventoryRequestInterface $updateListingInventoryRequest): array
    {
        $data = [];

        $data = self::applyPriceOnProperty($data, $updateListingInventoryRequest);
        $data = $this->applyProducts($data, $updateListingInventoryRequest);
        $data = self::applyQuantityOnProperty($data, $updateListingInventoryRequest);
        $data = self::applyReadinessStateOnProperty($data, $updateListingInventoryRequest);
        $data = self::applySkuOnProperty($data, $updateListingInventoryRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyPriceOnProperty(array $data, UpdateListingInventoryRequestInterface $updateListingInventoryRequest): array
    {
        $value = $updateListingInventoryRequest->getPriceOnProperty();
        if (empty($value)) {
            return $data;
        }
        $data[self::KEY_PRICE_ON_PROPERTY] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function applyProducts(array $data, UpdateListingInventoryRequestInterface $updateListingInventoryRequest): array
    {
        $data[self::KEY_PRODUCTS] = $this->listingInventoryProductRequestsSerializer->serialize($updateListingInventoryRequest->getProducts());

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyQuantityOnProperty(array $data, UpdateListingInventoryRequestInterface $updateListingInventoryRequest): array
    {
        $value = $updateListingInventoryRequest->getQuantityOnProperty();
        if (empty($value)) {
            return $data;
        }
        $data[self::KEY_QUANTITY_ON_PROPERTY] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyReadinessStateOnProperty(array $data, UpdateListingInventoryRequestInterface $updateListingInventoryRequest): array
    {
        $value = $updateListingInventoryRequest->getReadinessStateOnProperty();
        if (empty($value)) {
            return $data;
        }
        $data[self::KEY_READINESS_STATE_ON_PROPERTY] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applySkuOnProperty(array $data, UpdateListingInventoryRequestInterface $updateListingInventoryRequest): array
    {
        $value = $updateListingInventoryRequest->getSkuOnProperty();
        if (empty($value)) {
            return $data;
        }
        $data[self::KEY_SKU_ON_PROPERTY] = $value;

        return $data;
    }
}
