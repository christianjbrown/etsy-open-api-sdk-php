<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\RefundInterface;
use ChristianBrown\Etsy\Transformer\RefundsTransformer;
use ChristianBrown\Etsy\Transformer\RefundsTransformerInterface;
use ChristianBrown\Etsy\Transformer\RefundTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(RefundsTransformer::class)]
final class RefundsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-refund-1'], ['test-refund-2']];

        $refund1 = self::createStub(RefundInterface::class);
        $refund2 = self::createStub(RefundInterface::class);

        $refundTransformer = self::createStub(RefundTransformerInterface::class);
        $refundTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-refund-1'], $refund1],
                    [['test-refund-2'], $refund2],
                ]
            );

        $transformer = new RefundsTransformer($refundTransformer);

        self::assertSame([$refund1, $refund2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $refundTransformer = self::createStub(RefundTransformerInterface::class);

        $transformer = new RefundsTransformer($refundTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $refundTransformer = self::createStub(RefundTransformerInterface::class);

        $transformer = new RefundsTransformer($refundTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(RefundsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, RefundsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
