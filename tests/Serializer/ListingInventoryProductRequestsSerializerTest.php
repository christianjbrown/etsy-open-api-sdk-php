<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductRequestInterface;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductRequestsSerializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingInventoryProductRequestsSerializer::class)]
final class ListingInventoryProductRequestsSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $first = self::createStub(ListingInventoryProductRequestInterface::class);
        $second = self::createStub(ListingInventoryProductRequestInterface::class);

        $listingInventoryProductRequestSerializer = self::createStub(ListingInventoryProductRequestSerializerInterface::class);
        $listingInventoryProductRequestSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$first, ['first' => 'test-1']],
                    [$second, ['second' => 'test-2']],
                ]
            );

        $serializer = new ListingInventoryProductRequestsSerializer($listingInventoryProductRequestSerializer);

        self::assertSame([['first' => 'test-1'], ['second' => 'test-2']], $serializer->serialize([$first, $second]));
    }

    public function testSerializeEmpty(): void
    {
        $serializer = new ListingInventoryProductRequestsSerializer(self::createStub(ListingInventoryProductRequestSerializerInterface::class));

        self::assertSame([], $serializer->serialize([]));
    }
}
