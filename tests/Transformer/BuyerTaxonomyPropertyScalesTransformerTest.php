<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyScaleInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyScalesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyScalesTransformerInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyScaleTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(BuyerTaxonomyPropertyScalesTransformer::class)]
final class BuyerTaxonomyPropertyScalesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['scale-1'], ['scale-2']];

        $scale1 = self::createStub(BuyerTaxonomyPropertyScaleInterface::class);
        $scale2 = self::createStub(BuyerTaxonomyPropertyScaleInterface::class);

        $scaleTransformer = self::createStub(BuyerTaxonomyPropertyScaleTransformerInterface::class);
        $scaleTransformer->method('transform')
            ->willReturnMap(
                [
                    [['scale-1'], $scale1],
                    [['scale-2'], $scale2],
                ]
            );

        $transformer = new BuyerTaxonomyPropertyScalesTransformer($scaleTransformer);

        self::assertSame([$scale1, $scale2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $scaleTransformer = self::createStub(BuyerTaxonomyPropertyScaleTransformerInterface::class);

        $transformer = new BuyerTaxonomyPropertyScalesTransformer($scaleTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $scaleTransformer = self::createStub(BuyerTaxonomyPropertyScaleTransformerInterface::class);

        $transformer = new BuyerTaxonomyPropertyScalesTransformer($scaleTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyPropertyScalesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, BuyerTaxonomyPropertyScalesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
