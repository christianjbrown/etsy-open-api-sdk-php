<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationsTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopShippingProfileDestinationsTransformer::class)]
final class ShopShippingProfileDestinationsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['destination-1'], ['destination-2']];

        $destination1 = self::createStub(ShopShippingProfileDestinationInterface::class);
        $destination2 = self::createStub(ShopShippingProfileDestinationInterface::class);

        $destinationTransformer = self::createStub(ShopShippingProfileDestinationTransformerInterface::class);
        $destinationTransformer->method('transform')
            ->willReturnMap(
                [
                    [['destination-1'], $destination1],
                    [['destination-2'], $destination2],
                ]
            );

        $transformer = new ShopShippingProfileDestinationsTransformer($destinationTransformer);

        self::assertSame([$destination1, $destination2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $destinationTransformer = self::createStub(ShopShippingProfileDestinationTransformerInterface::class);

        $transformer = new ShopShippingProfileDestinationsTransformer($destinationTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $destinationTransformer = self::createStub(ShopShippingProfileDestinationTransformerInterface::class);

        $transformer = new ShopShippingProfileDestinationsTransformer($destinationTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopShippingProfileDestinationsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShopShippingProfileDestinationsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
