<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingVariationImageInterface;
use ChristianBrown\Etsy\Transformer\ListingVariationImagesTransformer;
use ChristianBrown\Etsy\Transformer\ListingVariationImagesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingVariationImageTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingVariationImagesTransformer::class)]
final class ListingVariationImagesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-variation-1'], ['test-variation-2']];

        $variation1 = self::createStub(ListingVariationImageInterface::class);
        $variation2 = self::createStub(ListingVariationImageInterface::class);

        $variationTransformer = self::createStub(ListingVariationImageTransformerInterface::class);
        $variationTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-variation-1'], $variation1],
                    [['test-variation-2'], $variation2],
                ]
            );

        $transformer = new ListingVariationImagesTransformer($variationTransformer);

        self::assertSame([$variation1, $variation2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $variationTransformer = self::createStub(ListingVariationImageTransformerInterface::class);

        $transformer = new ListingVariationImagesTransformer($variationTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $variationTransformer = self::createStub(ListingVariationImageTransformerInterface::class);

        $transformer = new ListingVariationImagesTransformer($variationTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVariationImagesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ListingVariationImagesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
