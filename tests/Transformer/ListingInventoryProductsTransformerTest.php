<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryProductInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductsTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingInventoryProductsTransformer::class)]
final class ListingInventoryProductsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-product-1'], ['test-product-2']];

        $product1 = self::createStub(ListingInventoryProductInterface::class);
        $product2 = self::createStub(ListingInventoryProductInterface::class);

        $productTransformer = self::createStub(ListingInventoryProductTransformerInterface::class);
        $productTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-product-1'], $product1],
                    [['test-product-2'], $product2],
                ]
            );

        $transformer = new ListingInventoryProductsTransformer($productTransformer);

        self::assertSame([$product1, $product2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $productTransformer = self::createStub(ListingInventoryProductTransformerInterface::class);

        $transformer = new ListingInventoryProductsTransformer($productTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $productTransformer = self::createStub(ListingInventoryProductTransformerInterface::class);

        $transformer = new ListingInventoryProductsTransformer($productTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingInventoryProductsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ListingInventoryProductsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
