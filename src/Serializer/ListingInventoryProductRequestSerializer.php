<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductRequestInterface;

final class ListingInventoryProductRequestSerializer implements ListingInventoryProductRequestSerializerInterface
{
    private ListingInventoryProductOfferingRequestsSerializerInterface $listingInventoryProductOfferingRequestsSerializer;
    private ListingInventoryProductPropertyValueRequestsSerializerInterface $listingInventoryProductPropertyValueRequestsSerializer;

    public function __construct(ListingInventoryProductOfferingRequestsSerializerInterface $listingInventoryProductOfferingRequestsSerializer, ListingInventoryProductPropertyValueRequestsSerializerInterface $listingInventoryProductPropertyValueRequestsSerializer)
    {
        $this->listingInventoryProductOfferingRequestsSerializer = $listingInventoryProductOfferingRequestsSerializer;
        $this->listingInventoryProductPropertyValueRequestsSerializer = $listingInventoryProductPropertyValueRequestsSerializer;
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(ListingInventoryProductRequestInterface $listingInventoryProductRequest): array
    {
        $data = [];

        $data = $this->applyOfferings($data, $listingInventoryProductRequest);
        $data = $this->applyPropertyValues($data, $listingInventoryProductRequest);
        $data = self::applySku($data, $listingInventoryProductRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function applyOfferings(array $data, ListingInventoryProductRequestInterface $listingInventoryProductRequest): array
    {
        $data[self::KEY_OFFERINGS] = $this->listingInventoryProductOfferingRequestsSerializer->serialize($listingInventoryProductRequest->getOfferings());

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function applyPropertyValues(array $data, ListingInventoryProductRequestInterface $listingInventoryProductRequest): array
    {
        $data[self::KEY_PROPERTY_VALUES] = $this->listingInventoryProductPropertyValueRequestsSerializer->serialize($listingInventoryProductRequest->getPropertyValues());

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applySku(array $data, ListingInventoryProductRequestInterface $listingInventoryProductRequest): array
    {
        $value = $listingInventoryProductRequest->getSku();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SKU] = $value;

        return $data;
    }
}
