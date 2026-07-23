<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopInterface;
use ChristianBrown\Etsy\Transformer\ShopsTransformer;
use ChristianBrown\Etsy\Transformer\ShopsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopsTransformer::class)]
final class ShopsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-shop-1'], ['test-shop-2']];

        $shop1 = self::createStub(ShopInterface::class);
        $shop2 = self::createStub(ShopInterface::class);

        $shopTransformer = self::createStub(ShopTransformerInterface::class);
        $shopTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-shop-1'], $shop1],
                    [['test-shop-2'], $shop2],
                ]
            );

        $transformer = new ShopsTransformer($shopTransformer);

        self::assertSame([$shop1, $shop2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $shopTransformer = self::createStub(ShopTransformerInterface::class);

        $transformer = new ShopsTransformer($shopTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $shopTransformer = self::createStub(ShopTransformerInterface::class);

        $transformer = new ShopsTransformer($shopTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShopsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
