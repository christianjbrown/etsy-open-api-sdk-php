<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingRequestInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductPropertyValueRequestInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductRequest;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductOfferingRequestsSerializerInterface;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductPropertyValueRequestsSerializerInterface;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingInventoryProductRequest::class)]
#[CoversClass(ListingInventoryProductRequestSerializer::class)]
final class ListingInventoryProductRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $offerings = [self::createStub(ListingInventoryProductOfferingRequestInterface::class)];
        $propertyValues = [self::createStub(ListingInventoryProductPropertyValueRequestInterface::class)];
        $sku = 'test-sku';

        $listingInventoryProductOfferingRequestsSerializer = self::createStub(ListingInventoryProductOfferingRequestsSerializerInterface::class);
        $listingInventoryProductOfferingRequestsSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$offerings, [['test-offerings']]],
                ]
            );
        $listingInventoryProductPropertyValueRequestsSerializer = self::createStub(ListingInventoryProductPropertyValueRequestsSerializerInterface::class);
        $listingInventoryProductPropertyValueRequestsSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$propertyValues, [['test-propertyValues']]],
                ]
            );

        $listingInventoryProductRequest = (new ListingInventoryProductRequest($offerings, $propertyValues))
            ->setOfferings($offerings)
            ->setPropertyValues($propertyValues)
            ->setSku($sku);

        $serializer = new ListingInventoryProductRequestSerializer($listingInventoryProductOfferingRequestsSerializer, $listingInventoryProductPropertyValueRequestsSerializer);

        $expected = [
            ListingInventoryProductRequestSerializerInterface::KEY_OFFERINGS => [['test-offerings']],
            ListingInventoryProductRequestSerializerInterface::KEY_PROPERTY_VALUES => [['test-propertyValues']],
            ListingInventoryProductRequestSerializerInterface::KEY_SKU => $sku,
        ];

        self::assertSame($expected, $serializer->serialize($listingInventoryProductRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $offerings = [self::createStub(ListingInventoryProductOfferingRequestInterface::class)];
        $propertyValues = [self::createStub(ListingInventoryProductPropertyValueRequestInterface::class)];

        $listingInventoryProductOfferingRequestsSerializer = self::createStub(ListingInventoryProductOfferingRequestsSerializerInterface::class);
        $listingInventoryProductOfferingRequestsSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$offerings, [['test-offerings']]],
                ]
            );
        $listingInventoryProductPropertyValueRequestsSerializer = self::createStub(ListingInventoryProductPropertyValueRequestsSerializerInterface::class);
        $listingInventoryProductPropertyValueRequestsSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$propertyValues, [['test-propertyValues']]],
                ]
            );

        $listingInventoryProductRequest = new ListingInventoryProductRequest($offerings, $propertyValues);

        $serializer = new ListingInventoryProductRequestSerializer($listingInventoryProductOfferingRequestsSerializer, $listingInventoryProductPropertyValueRequestsSerializer);

        $expected = [
            ListingInventoryProductRequestSerializerInterface::KEY_OFFERINGS => [['test-offerings']],
            ListingInventoryProductRequestSerializerInterface::KEY_PROPERTY_VALUES => [['test-propertyValues']],
        ];

        self::assertSame($expected, $serializer->serialize($listingInventoryProductRequest));
    }
}
