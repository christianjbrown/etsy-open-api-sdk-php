<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductPropertyValueRequest;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductPropertyValueRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductPropertyValueRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingInventoryProductPropertyValueRequest::class)]
#[CoversClass(ListingInventoryProductPropertyValueRequestSerializer::class)]
final class ListingInventoryProductPropertyValueRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $propertyId = 1;
        $valueIds = [2, 3];
        $values = ['test-values-1', 'test-values-2'];
        $propertyName = 'test-propertyName';
        $scaleId = 4;

        $listingInventoryProductPropertyValueRequest = (new ListingInventoryProductPropertyValueRequest($propertyId, $valueIds, $values))
            ->setPropertyId($propertyId)
            ->setValueIds($valueIds)
            ->setValues($values)
            ->setPropertyName($propertyName)
            ->setScaleId($scaleId);

        $serializer = new ListingInventoryProductPropertyValueRequestSerializer();

        $expected = [
            ListingInventoryProductPropertyValueRequestSerializerInterface::KEY_PROPERTY_ID => $propertyId,
            ListingInventoryProductPropertyValueRequestSerializerInterface::KEY_PROPERTY_NAME => $propertyName,
            ListingInventoryProductPropertyValueRequestSerializerInterface::KEY_SCALE_ID => $scaleId,
            ListingInventoryProductPropertyValueRequestSerializerInterface::KEY_VALUE_IDS => $valueIds,
            ListingInventoryProductPropertyValueRequestSerializerInterface::KEY_VALUES => $values,
        ];

        self::assertSame($expected, $serializer->serialize($listingInventoryProductPropertyValueRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $propertyId = 1;
        $valueIds = [2, 3];
        $values = ['test-values-1', 'test-values-2'];

        $listingInventoryProductPropertyValueRequest = new ListingInventoryProductPropertyValueRequest($propertyId, $valueIds, $values);

        $serializer = new ListingInventoryProductPropertyValueRequestSerializer();

        $expected = [
            ListingInventoryProductPropertyValueRequestSerializerInterface::KEY_PROPERTY_ID => $propertyId,
            ListingInventoryProductPropertyValueRequestSerializerInterface::KEY_VALUE_IDS => $valueIds,
            ListingInventoryProductPropertyValueRequestSerializerInterface::KEY_VALUES => $values,
        ];

        self::assertSame($expected, $serializer->serialize($listingInventoryProductPropertyValueRequest));
    }
}
