<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ReceiptShipmentCustomsItemRequestInterface;
use ChristianBrown\Etsy\Serializer\ReceiptShipmentCustomsItemRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\ReceiptShipmentCustomsItemRequestsSerializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReceiptShipmentCustomsItemRequestsSerializer::class)]
final class ReceiptShipmentCustomsItemRequestsSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $first = self::createStub(ReceiptShipmentCustomsItemRequestInterface::class);
        $second = self::createStub(ReceiptShipmentCustomsItemRequestInterface::class);

        $receiptShipmentCustomsItemRequestSerializer = self::createStub(ReceiptShipmentCustomsItemRequestSerializerInterface::class);
        $receiptShipmentCustomsItemRequestSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$first, ['first' => 'test-1']],
                    [$second, ['second' => 'test-2']],
                ]
            );

        $serializer = new ReceiptShipmentCustomsItemRequestsSerializer($receiptShipmentCustomsItemRequestSerializer);

        self::assertSame([['first' => 'test-1'], ['second' => 'test-2']], $serializer->serialize([$first, $second]));
    }

    public function testSerializeEmpty(): void
    {
        $serializer = new ReceiptShipmentCustomsItemRequestsSerializer(self::createStub(ReceiptShipmentCustomsItemRequestSerializerInterface::class));

        self::assertSame([], $serializer->serialize([]));
    }
}
