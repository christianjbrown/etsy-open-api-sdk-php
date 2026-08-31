<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductRequestInterface;
use ChristianBrown\Etsy\Model\UpdateListingInventoryRequest;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductRequestsSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingInventoryRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateListingInventoryRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateListingInventoryRequest::class)]
#[CoversClass(UpdateListingInventoryRequestSerializer::class)]
final class UpdateListingInventoryRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $products = [self::createStub(ListingInventoryProductRequestInterface::class)];
        $priceOnProperty = [1, 2];
        $quantityOnProperty = [3, 4];
        $readinessStateOnProperty = [5, 6];
        $skuOnProperty = [7, 8];

        $listingInventoryProductRequestsSerializer = self::createStub(ListingInventoryProductRequestsSerializerInterface::class);
        $listingInventoryProductRequestsSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$products, [['test-products']]],
                ]
            );

        $updateListingInventoryRequest = (new UpdateListingInventoryRequest($products))
            ->setProducts($products)
            ->setPriceOnProperty($priceOnProperty)
            ->setQuantityOnProperty($quantityOnProperty)
            ->setReadinessStateOnProperty($readinessStateOnProperty)
            ->setSkuOnProperty($skuOnProperty);

        $serializer = new UpdateListingInventoryRequestSerializer($listingInventoryProductRequestsSerializer);

        $expected = [
            UpdateListingInventoryRequestSerializerInterface::KEY_PRICE_ON_PROPERTY => $priceOnProperty,
            UpdateListingInventoryRequestSerializerInterface::KEY_PRODUCTS => [['test-products']],
            UpdateListingInventoryRequestSerializerInterface::KEY_QUANTITY_ON_PROPERTY => $quantityOnProperty,
            UpdateListingInventoryRequestSerializerInterface::KEY_READINESS_STATE_ON_PROPERTY => $readinessStateOnProperty,
            UpdateListingInventoryRequestSerializerInterface::KEY_SKU_ON_PROPERTY => $skuOnProperty,
        ];

        self::assertSame($expected, $serializer->serialize($updateListingInventoryRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $products = [self::createStub(ListingInventoryProductRequestInterface::class)];

        $listingInventoryProductRequestsSerializer = self::createStub(ListingInventoryProductRequestsSerializerInterface::class);
        $listingInventoryProductRequestsSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$products, [['test-products']]],
                ]
            );

        $updateListingInventoryRequest = new UpdateListingInventoryRequest($products);

        $serializer = new UpdateListingInventoryRequestSerializer($listingInventoryProductRequestsSerializer);

        $expected = [
            UpdateListingInventoryRequestSerializerInterface::KEY_PRODUCTS => [['test-products']],
        ];

        self::assertSame($expected, $serializer->serialize($updateListingInventoryRequest));
    }
}
