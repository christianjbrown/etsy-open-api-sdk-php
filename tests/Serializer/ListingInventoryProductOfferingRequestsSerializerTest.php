<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingRequestInterface;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductOfferingRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductOfferingRequestsSerializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingInventoryProductOfferingRequestsSerializer::class)]
final class ListingInventoryProductOfferingRequestsSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $first = self::createStub(ListingInventoryProductOfferingRequestInterface::class);
        $second = self::createStub(ListingInventoryProductOfferingRequestInterface::class);

        $listingInventoryProductOfferingRequestSerializer = self::createStub(ListingInventoryProductOfferingRequestSerializerInterface::class);
        $listingInventoryProductOfferingRequestSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$first, ['first' => 'test-1']],
                    [$second, ['second' => 'test-2']],
                ]
            );

        $serializer = new ListingInventoryProductOfferingRequestsSerializer($listingInventoryProductOfferingRequestSerializer);

        self::assertSame([['first' => 'test-1'], ['second' => 'test-2']], $serializer->serialize([$first, $second]));
    }

    public function testSerializeEmpty(): void
    {
        $serializer = new ListingInventoryProductOfferingRequestsSerializer(self::createStub(ListingInventoryProductOfferingRequestSerializerInterface::class));

        self::assertSame([], $serializer->serialize([]));
    }
}
