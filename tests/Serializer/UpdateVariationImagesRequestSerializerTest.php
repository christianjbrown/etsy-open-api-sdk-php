<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ListingVariationImageRequestInterface;
use ChristianBrown\Etsy\Model\UpdateVariationImagesRequest;
use ChristianBrown\Etsy\Serializer\ListingVariationImageRequestsSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateVariationImagesRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateVariationImagesRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateVariationImagesRequest::class)]
#[CoversClass(UpdateVariationImagesRequestSerializer::class)]
final class UpdateVariationImagesRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $variationImages = [self::createStub(ListingVariationImageRequestInterface::class)];

        $listingVariationImageRequestsSerializer = self::createStub(ListingVariationImageRequestsSerializerInterface::class);
        $listingVariationImageRequestsSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$variationImages, [['test-variationImages']]],
                ]
            );

        $updateVariationImagesRequest = (new UpdateVariationImagesRequest($variationImages))
            ->setVariationImages($variationImages);

        $serializer = new UpdateVariationImagesRequestSerializer($listingVariationImageRequestsSerializer);

        $expected = [
            UpdateVariationImagesRequestSerializerInterface::KEY_VARIATION_IMAGES => [['test-variationImages']],
        ];

        self::assertSame($expected, $serializer->serialize($updateVariationImagesRequest));
    }
}
