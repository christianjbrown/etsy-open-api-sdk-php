<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TaxonomyPropertyScaleInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyScalesTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyScalesTransformerInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyScaleTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(TaxonomyPropertyScalesTransformer::class)]
final class TaxonomyPropertyScalesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['scale-1'], ['scale-2']];

        $scale1 = self::createStub(TaxonomyPropertyScaleInterface::class);
        $scale2 = self::createStub(TaxonomyPropertyScaleInterface::class);

        $scaleTransformer = self::createStub(TaxonomyPropertyScaleTransformerInterface::class);
        $scaleTransformer->method('transform')
            ->willReturnMap(
                [
                    [['scale-1'], $scale1],
                    [['scale-2'], $scale2],
                ]
            );

        $transformer = new TaxonomyPropertyScalesTransformer($scaleTransformer);

        self::assertSame([$scale1, $scale2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $scaleTransformer = self::createStub(TaxonomyPropertyScaleTransformerInterface::class);

        $transformer = new TaxonomyPropertyScalesTransformer($scaleTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $scaleTransformer = self::createStub(TaxonomyPropertyScaleTransformerInterface::class);

        $transformer = new TaxonomyPropertyScalesTransformer($scaleTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(TaxonomyPropertyScalesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, TaxonomyPropertyScalesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
