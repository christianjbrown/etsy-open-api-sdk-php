<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAdjustmentInterface;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentsTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(PaymentAdjustmentsTransformer::class)]
final class PaymentAdjustmentsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-adjustment-1'], ['test-adjustment-2']];

        $adjustment1 = self::createStub(PaymentAdjustmentInterface::class);
        $adjustment2 = self::createStub(PaymentAdjustmentInterface::class);

        $adjustmentTransformer = self::createStub(PaymentAdjustmentTransformerInterface::class);
        $adjustmentTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-adjustment-1'], $adjustment1],
                    [['test-adjustment-2'], $adjustment2],
                ]
            );

        $transformer = new PaymentAdjustmentsTransformer($adjustmentTransformer);

        self::assertSame([$adjustment1, $adjustment2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $adjustmentTransformer = self::createStub(PaymentAdjustmentTransformerInterface::class);

        $transformer = new PaymentAdjustmentsTransformer($adjustmentTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $adjustmentTransformer = self::createStub(PaymentAdjustmentTransformerInterface::class);

        $transformer = new PaymentAdjustmentsTransformer($adjustmentTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PaymentAdjustmentsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, PaymentAdjustmentsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
