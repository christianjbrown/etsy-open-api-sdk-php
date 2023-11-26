<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\Receipt;
use ChristianBrown\Etsy\Model\TransactionInterface;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformerInterface;
use ChristianBrown\Etsy\Transformer\TransactionsTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(Receipt::class)]
#[CoversClass(ReceiptTransformer::class)]
final class ReceiptTransformerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $dataTransactions = [['test-transaction-1'], ['test-transaction-2']];
        $data = [
            ReceiptTransformerInterface::DATA_KEY_TRANSACTIONS => [['test-transaction-1'], ['test-transaction-2']],
        ];

        $transaction1 = $this->createMock(TransactionInterface::class);
        $transaction2 = $this->createMock(TransactionInterface::class);

        $transactionsTransformer = $this->createMock(TransactionsTransformerInterface::class);
        $transactionsTransformer->method('transform')
            ->with($dataTransactions)
            ->willReturn([$transaction1, $transaction2]);

        $transformer = new ReceiptTransformer($transactionsTransformer);
        $actual = $transformer->transform($data);

        self::assertSame([$transaction1, $transaction2], $actual->getTransactions());
    }
}
