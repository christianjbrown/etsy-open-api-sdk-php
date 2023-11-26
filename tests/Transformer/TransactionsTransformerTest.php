<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\TransactionInterface;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(TransactionsTransformer::class)]
final class TransactionsTransformerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $transaction1 = $this->createMock(TransactionInterface::class);
        $transaction2 = $this->createMock(TransactionInterface::class);

        $transactionTransformer = $this->createMock(TransactionTransformerInterface::class);
        $transactionTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-data-1'], $transaction1],
                    [['test-data-2'], $transaction2],
                ]
            );

        $transformer = new TransactionsTransformer($transactionTransformer);
        $actual = $transformer->transform([['test-data-1'], ['test-data-2']]);

        self::assertSame([$transaction1, $transaction2], $actual);
    }
}
