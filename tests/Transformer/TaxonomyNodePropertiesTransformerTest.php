<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TaxonomyNodePropertyInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertiesTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertiesTransformerInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertyTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(TaxonomyNodePropertiesTransformer::class)]
final class TaxonomyNodePropertiesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['property-1'], ['property-2']];

        $property1 = self::createStub(TaxonomyNodePropertyInterface::class);
        $property2 = self::createStub(TaxonomyNodePropertyInterface::class);

        $propertyTransformer = self::createStub(TaxonomyNodePropertyTransformerInterface::class);
        $propertyTransformer->method('transform')
            ->willReturnMap(
                [
                    [['property-1'], $property1],
                    [['property-2'], $property2],
                ]
            );

        $transformer = new TaxonomyNodePropertiesTransformer($propertyTransformer);

        self::assertSame([$property1, $property2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $propertyTransformer = self::createStub(TaxonomyNodePropertyTransformerInterface::class);

        $transformer = new TaxonomyNodePropertiesTransformer($propertyTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $propertyTransformer = self::createStub(TaxonomyNodePropertyTransformerInterface::class);

        $transformer = new TaxonomyNodePropertiesTransformer($propertyTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(TaxonomyNodePropertiesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, TaxonomyNodePropertiesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
