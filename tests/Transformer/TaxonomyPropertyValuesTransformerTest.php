<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TaxonomyPropertyValueInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyValuesTransformerInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyValueTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(TaxonomyPropertyValuesTransformer::class)]
final class TaxonomyPropertyValuesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['value-1'], ['value-2']];

        $value1 = self::createStub(TaxonomyPropertyValueInterface::class);
        $value2 = self::createStub(TaxonomyPropertyValueInterface::class);

        $valueTransformer = self::createStub(TaxonomyPropertyValueTransformerInterface::class);
        $valueTransformer->method('transform')
            ->willReturnMap(
                [
                    [['value-1'], $value1],
                    [['value-2'], $value2],
                ]
            );

        $transformer = new TaxonomyPropertyValuesTransformer($valueTransformer);

        self::assertSame([$value1, $value2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $valueTransformer = self::createStub(TaxonomyPropertyValueTransformerInterface::class);

        $transformer = new TaxonomyPropertyValuesTransformer($valueTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $valueTransformer = self::createStub(TaxonomyPropertyValueTransformerInterface::class);

        $transformer = new TaxonomyPropertyValuesTransformer($valueTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(TaxonomyPropertyValuesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, TaxonomyPropertyValuesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
