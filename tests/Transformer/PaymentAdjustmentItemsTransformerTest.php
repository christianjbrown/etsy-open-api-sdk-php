<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAdjustmentItemInterface;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemsTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(PaymentAdjustmentItemsTransformer::class)]
final class PaymentAdjustmentItemsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-item-1'], ['test-item-2']];

        $item1 = self::createStub(PaymentAdjustmentItemInterface::class);
        $item2 = self::createStub(PaymentAdjustmentItemInterface::class);

        $itemTransformer = self::createStub(PaymentAdjustmentItemTransformerInterface::class);
        $itemTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-item-1'], $item1],
                    [['test-item-2'], $item2],
                ]
            );

        $transformer = new PaymentAdjustmentItemsTransformer($itemTransformer);

        self::assertSame([$item1, $item2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $itemTransformer = self::createStub(PaymentAdjustmentItemTransformerInterface::class);

        $transformer = new PaymentAdjustmentItemsTransformer($itemTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $itemTransformer = self::createStub(PaymentAdjustmentItemTransformerInterface::class);

        $transformer = new PaymentAdjustmentItemsTransformer($itemTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PaymentAdjustmentItemsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, PaymentAdjustmentItemsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
