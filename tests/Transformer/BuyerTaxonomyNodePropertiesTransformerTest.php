<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodePropertyInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertiesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertiesTransformerInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertyTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(BuyerTaxonomyNodePropertiesTransformer::class)]
final class BuyerTaxonomyNodePropertiesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['property-1'], ['property-2']];

        $property1 = self::createStub(BuyerTaxonomyNodePropertyInterface::class);
        $property2 = self::createStub(BuyerTaxonomyNodePropertyInterface::class);

        $propertyTransformer = self::createStub(BuyerTaxonomyNodePropertyTransformerInterface::class);
        $propertyTransformer->method('transform')
            ->willReturnMap(
                [
                    [['property-1'], $property1],
                    [['property-2'], $property2],
                ]
            );

        $transformer = new BuyerTaxonomyNodePropertiesTransformer($propertyTransformer);

        self::assertSame([$property1, $property2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $propertyTransformer = self::createStub(BuyerTaxonomyNodePropertyTransformerInterface::class);

        $transformer = new BuyerTaxonomyNodePropertiesTransformer($propertyTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $propertyTransformer = self::createStub(BuyerTaxonomyNodePropertyTransformerInterface::class);

        $transformer = new BuyerTaxonomyNodePropertiesTransformer($propertyTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyNodePropertiesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, BuyerTaxonomyNodePropertiesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
