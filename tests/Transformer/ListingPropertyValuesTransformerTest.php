<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingPropertyValueInterface;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingPropertyValueTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingPropertyValuesTransformer::class)]
final class ListingPropertyValuesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-property-1'], ['test-property-2']];

        $propertyValue1 = self::createStub(ListingPropertyValueInterface::class);
        $propertyValue2 = self::createStub(ListingPropertyValueInterface::class);

        $propertyValueTransformer = self::createStub(ListingPropertyValueTransformerInterface::class);
        $propertyValueTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-property-1'], $propertyValue1],
                    [['test-property-2'], $propertyValue2],
                ]
            );

        $transformer = new ListingPropertyValuesTransformer($propertyValueTransformer);

        self::assertSame([$propertyValue1, $propertyValue2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $propertyValueTransformer = self::createStub(ListingPropertyValueTransformerInterface::class);

        $transformer = new ListingPropertyValuesTransformer($propertyValueTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $propertyValueTransformer = self::createStub(ListingPropertyValueTransformerInterface::class);

        $transformer = new ListingPropertyValuesTransformer($propertyValueTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingPropertyValuesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ListingPropertyValuesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
