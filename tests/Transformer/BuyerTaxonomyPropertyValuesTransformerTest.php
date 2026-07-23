<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyValueInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyValuesTransformerInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyValueTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(BuyerTaxonomyPropertyValuesTransformer::class)]
final class BuyerTaxonomyPropertyValuesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['value-1'], ['value-2']];

        $value1 = self::createStub(BuyerTaxonomyPropertyValueInterface::class);
        $value2 = self::createStub(BuyerTaxonomyPropertyValueInterface::class);

        $valueTransformer = self::createStub(BuyerTaxonomyPropertyValueTransformerInterface::class);
        $valueTransformer->method('transform')
            ->willReturnMap(
                [
                    [['value-1'], $value1],
                    [['value-2'], $value2],
                ]
            );

        $transformer = new BuyerTaxonomyPropertyValuesTransformer($valueTransformer);

        self::assertSame([$value1, $value2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $valueTransformer = self::createStub(BuyerTaxonomyPropertyValueTransformerInterface::class);

        $transformer = new BuyerTaxonomyPropertyValuesTransformer($valueTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $valueTransformer = self::createStub(BuyerTaxonomyPropertyValueTransformerInterface::class);

        $transformer = new BuyerTaxonomyPropertyValuesTransformer($valueTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyPropertyValuesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, BuyerTaxonomyPropertyValuesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
