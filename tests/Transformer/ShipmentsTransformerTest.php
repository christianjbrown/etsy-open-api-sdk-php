<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShipmentInterface;
use ChristianBrown\Etsy\Transformer\ShipmentsTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShipmentTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShipmentsTransformer::class)]
final class ShipmentsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-shipment-1'], ['test-shipment-2']];

        $shipment1 = self::createStub(ShipmentInterface::class);
        $shipment2 = self::createStub(ShipmentInterface::class);

        $shipmentTransformer = self::createStub(ShipmentTransformerInterface::class);
        $shipmentTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-shipment-1'], $shipment1],
                    [['test-shipment-2'], $shipment2],
                ]
            );

        $transformer = new ShipmentsTransformer($shipmentTransformer);

        self::assertSame([$shipment1, $shipment2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $shipmentTransformer = self::createStub(ShipmentTransformerInterface::class);

        $transformer = new ShipmentsTransformer($shipmentTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $shipmentTransformer = self::createStub(ShipmentTransformerInterface::class);

        $transformer = new ShipmentsTransformer($shipmentTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShipmentsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShipmentsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
