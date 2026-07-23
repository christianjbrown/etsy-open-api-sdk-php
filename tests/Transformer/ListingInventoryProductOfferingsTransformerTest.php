<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingsTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingInventoryProductOfferingsTransformer::class)]
final class ListingInventoryProductOfferingsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-offering-1'], ['test-offering-2']];

        $offering1 = self::createStub(ListingInventoryProductOfferingInterface::class);
        $offering2 = self::createStub(ListingInventoryProductOfferingInterface::class);

        $offeringTransformer = self::createStub(ListingInventoryProductOfferingTransformerInterface::class);
        $offeringTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-offering-1'], $offering1],
                    [['test-offering-2'], $offering2],
                ]
            );

        $transformer = new ListingInventoryProductOfferingsTransformer($offeringTransformer);

        self::assertSame([$offering1, $offering2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $offeringTransformer = self::createStub(ListingInventoryProductOfferingTransformerInterface::class);

        $transformer = new ListingInventoryProductOfferingsTransformer($offeringTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $offeringTransformer = self::createStub(ListingInventoryProductOfferingTransformerInterface::class);

        $transformer = new ListingInventoryProductOfferingsTransformer($offeringTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingInventoryProductOfferingsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ListingInventoryProductOfferingsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
