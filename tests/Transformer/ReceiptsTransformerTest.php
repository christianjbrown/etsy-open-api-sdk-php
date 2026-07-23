<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ReceiptInterface;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ReceiptsTransformer::class)]
final class ReceiptsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-receipt-1'], ['test-receipt-2']];

        $receipt1 = self::createStub(ReceiptInterface::class);
        $receipt2 = self::createStub(ReceiptInterface::class);

        $receiptTransformer = self::createStub(ReceiptTransformerInterface::class);
        $receiptTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-receipt-1'], $receipt1],
                    [['test-receipt-2'], $receipt2],
                ]
            );

        $transformer = new ReceiptsTransformer($receiptTransformer);

        self::assertSame([$receipt1, $receipt2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $receiptTransformer = self::createStub(ReceiptTransformerInterface::class);

        $transformer = new ReceiptsTransformer($receiptTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $receiptTransformer = self::createStub(ReceiptTransformerInterface::class);

        $transformer = new ReceiptsTransformer($receiptTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReceiptsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ReceiptsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
