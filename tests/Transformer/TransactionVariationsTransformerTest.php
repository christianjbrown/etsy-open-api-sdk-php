<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TransactionVariationInterface;
use ChristianBrown\Etsy\Transformer\TransactionVariationsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationsTransformerInterface;
use ChristianBrown\Etsy\Transformer\TransactionVariationTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(TransactionVariationsTransformer::class)]
final class TransactionVariationsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-variation-1'], ['test-variation-2']];

        $variation1 = self::createStub(TransactionVariationInterface::class);
        $variation2 = self::createStub(TransactionVariationInterface::class);

        $variationTransformer = self::createStub(TransactionVariationTransformerInterface::class);
        $variationTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-variation-1'], $variation1],
                    [['test-variation-2'], $variation2],
                ]
            );

        $transformer = new TransactionVariationsTransformer($variationTransformer);

        self::assertSame([$variation1, $variation2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $variationTransformer = self::createStub(TransactionVariationTransformerInterface::class);

        $transformer = new TransactionVariationsTransformer($variationTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $variationTransformer = self::createStub(TransactionVariationTransformerInterface::class);

        $transformer = new TransactionVariationsTransformer($variationTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(TransactionVariationsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, TransactionVariationsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
