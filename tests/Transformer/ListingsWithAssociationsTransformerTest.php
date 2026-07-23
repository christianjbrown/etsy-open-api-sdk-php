<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;
use ChristianBrown\Etsy\Transformer\ListingsWithAssociationsTransformer;
use ChristianBrown\Etsy\Transformer\ListingsWithAssociationsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingsWithAssociationsTransformer::class)]
final class ListingsWithAssociationsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-listing-1'], ['test-listing-2']];

        $listing1 = self::createStub(ListingWithAssociationsInterface::class);
        $listing2 = self::createStub(ListingWithAssociationsInterface::class);

        $listingTransformer = self::createStub(ListingWithAssociationsTransformerInterface::class);
        $listingTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-listing-1'], $listing1],
                    [['test-listing-2'], $listing2],
                ]
            );

        $transformer = new ListingsWithAssociationsTransformer($listingTransformer);

        self::assertSame([$listing1, $listing2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $listingTransformer = self::createStub(ListingWithAssociationsTransformerInterface::class);

        $transformer = new ListingsWithAssociationsTransformer($listingTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $listingTransformer = self::createStub(ListingWithAssociationsTransformerInterface::class);

        $transformer = new ListingsWithAssociationsTransformer($listingTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingsWithAssociationsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ListingsWithAssociationsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
