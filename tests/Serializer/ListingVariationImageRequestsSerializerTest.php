<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ListingVariationImageRequestInterface;
use ChristianBrown\Etsy\Serializer\ListingVariationImageRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\ListingVariationImageRequestsSerializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingVariationImageRequestsSerializer::class)]
final class ListingVariationImageRequestsSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $first = self::createStub(ListingVariationImageRequestInterface::class);
        $second = self::createStub(ListingVariationImageRequestInterface::class);

        $listingVariationImageRequestSerializer = self::createStub(ListingVariationImageRequestSerializerInterface::class);
        $listingVariationImageRequestSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$first, ['first' => 'test-1']],
                    [$second, ['second' => 'test-2']],
                ]
            );

        $serializer = new ListingVariationImageRequestsSerializer($listingVariationImageRequestSerializer);

        self::assertSame([['first' => 'test-1'], ['second' => 'test-2']], $serializer->serialize([$first, $second]));
    }

    public function testSerializeEmpty(): void
    {
        $serializer = new ListingVariationImageRequestsSerializer(self::createStub(ListingVariationImageRequestSerializerInterface::class));

        self::assertSame([], $serializer->serialize([]));
    }
}
