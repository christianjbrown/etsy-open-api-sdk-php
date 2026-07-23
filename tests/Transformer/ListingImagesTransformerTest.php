<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingImageInterface;
use ChristianBrown\Etsy\Transformer\ListingImagesTransformer;
use ChristianBrown\Etsy\Transformer\ListingImagesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingImageTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingImagesTransformer::class)]
final class ListingImagesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-image-1'], ['test-image-2']];

        $image1 = self::createStub(ListingImageInterface::class);
        $image2 = self::createStub(ListingImageInterface::class);

        $imageTransformer = self::createStub(ListingImageTransformerInterface::class);
        $imageTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-image-1'], $image1],
                    [['test-image-2'], $image2],
                ]
            );

        $transformer = new ListingImagesTransformer($imageTransformer);

        self::assertSame([$image1, $image2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $imageTransformer = self::createStub(ListingImageTransformerInterface::class);

        $transformer = new ListingImagesTransformer($imageTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $imageTransformer = self::createStub(ListingImageTransformerInterface::class);

        $transformer = new ListingImagesTransformer($imageTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingImagesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ListingImagesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
