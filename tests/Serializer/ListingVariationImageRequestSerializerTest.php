<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ListingVariationImageRequest;
use ChristianBrown\Etsy\Serializer\ListingVariationImageRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingVariationImageRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingVariationImageRequest::class)]
#[CoversClass(ListingVariationImageRequestSerializer::class)]
final class ListingVariationImageRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $propertyId = 1;
        $valueId = 2;
        $imageId = 3;

        $listingVariationImageRequest = (new ListingVariationImageRequest($propertyId, $valueId, $imageId))
            ->setPropertyId($propertyId)
            ->setValueId($valueId)
            ->setImageId($imageId);

        $serializer = new ListingVariationImageRequestSerializer();

        $expected = [
            ListingVariationImageRequestSerializerInterface::KEY_IMAGE_ID => $imageId,
            ListingVariationImageRequestSerializerInterface::KEY_PROPERTY_ID => $propertyId,
            ListingVariationImageRequestSerializerInterface::KEY_VALUE_ID => $valueId,
        ];

        self::assertSame($expected, $serializer->serialize($listingVariationImageRequest));
    }
}
