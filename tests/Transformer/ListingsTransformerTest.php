<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInterface;
use ChristianBrown\Etsy\Transformer\ListingsTransformer;
use ChristianBrown\Etsy\Transformer\ListingsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingsTransformer::class)]
final class ListingsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-listing-1'], ['test-listing-2']];

        $listing1 = self::createStub(ListingInterface::class);
        $listing2 = self::createStub(ListingInterface::class);

        $listingTransformer = self::createStub(ListingTransformerInterface::class);
        $listingTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-listing-1'], $listing1],
                    [['test-listing-2'], $listing2],
                ]
            );

        $transformer = new ListingsTransformer($listingTransformer);

        self::assertSame([$listing1, $listing2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $listingTransformer = self::createStub(ListingTransformerInterface::class);

        $transformer = new ListingsTransformer($listingTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $listingTransformer = self::createStub(ListingTransformerInterface::class);

        $transformer = new ListingsTransformer($listingTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ListingsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
