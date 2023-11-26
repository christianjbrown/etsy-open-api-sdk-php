<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\ReceiptInterface;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReceiptsTransformer::class)]
final class ReceiptsTransformerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $receipt1 = $this->createMock(ReceiptInterface::class);
        $receipt2 = $this->createMock(ReceiptInterface::class);

        $receiptTransformer = $this->createMock(ReceiptTransformerInterface::class);
        $receiptTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-data-1'], $receipt1],
                    [['test-data-2'], $receipt2],
                ]
            );

        $transformer = new ReceiptsTransformer($receiptTransformer);
        $actual = $transformer->transform([['test-data-1'], ['test-data-2']]);

        self::assertSame([$receipt1, $receipt2], $actual);
    }
}
