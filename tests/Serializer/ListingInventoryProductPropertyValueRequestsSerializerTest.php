<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductPropertyValueRequestInterface;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductPropertyValueRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductPropertyValueRequestsSerializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingInventoryProductPropertyValueRequestsSerializer::class)]
final class ListingInventoryProductPropertyValueRequestsSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $first = self::createStub(ListingInventoryProductPropertyValueRequestInterface::class);
        $second = self::createStub(ListingInventoryProductPropertyValueRequestInterface::class);

        $listingInventoryProductPropertyValueRequestSerializer = self::createStub(ListingInventoryProductPropertyValueRequestSerializerInterface::class);
        $listingInventoryProductPropertyValueRequestSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$first, ['first' => 'test-1']],
                    [$second, ['second' => 'test-2']],
                ]
            );

        $serializer = new ListingInventoryProductPropertyValueRequestsSerializer($listingInventoryProductPropertyValueRequestSerializer);

        self::assertSame([['first' => 'test-1'], ['second' => 'test-2']], $serializer->serialize([$first, $second]));
    }

    public function testSerializeEmpty(): void
    {
        $serializer = new ListingInventoryProductPropertyValueRequestsSerializer(self::createStub(ListingInventoryProductPropertyValueRequestSerializerInterface::class));

        self::assertSame([], $serializer->serialize([]));
    }
}
