<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShippingCarrierInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarriersTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarriersTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarrierTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShippingCarriersTransformer::class)]
final class ShippingCarriersTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['carrier-1'], ['carrier-2']];

        $carrier1 = self::createStub(ShippingCarrierInterface::class);
        $carrier2 = self::createStub(ShippingCarrierInterface::class);

        $carrierTransformer = self::createStub(ShippingCarrierTransformerInterface::class);
        $carrierTransformer->method('transform')
            ->willReturnMap(
                [
                    [['carrier-1'], $carrier1],
                    [['carrier-2'], $carrier2],
                ]
            );

        $transformer = new ShippingCarriersTransformer($carrierTransformer);

        self::assertSame([$carrier1, $carrier2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $carrierTransformer = self::createStub(ShippingCarrierTransformerInterface::class);

        $transformer = new ShippingCarriersTransformer($carrierTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $carrierTransformer = self::createStub(ShippingCarrierTransformerInterface::class);

        $transformer = new ShippingCarriersTransformer($carrierTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingCarriersTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShippingCarriersTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
