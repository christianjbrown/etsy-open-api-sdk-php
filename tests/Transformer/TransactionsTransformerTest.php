<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TransactionInterface;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\TransactionTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(TransactionsTransformer::class)]
final class TransactionsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-transaction-1'], ['test-transaction-2']];

        $transaction1 = self::createStub(TransactionInterface::class);
        $transaction2 = self::createStub(TransactionInterface::class);

        $transactionTransformer = self::createStub(TransactionTransformerInterface::class);
        $transactionTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-transaction-1'], $transaction1],
                    [['test-transaction-2'], $transaction2],
                ]
            );

        $transformer = new TransactionsTransformer($transactionTransformer);

        self::assertSame([$transaction1, $transaction2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $transactionTransformer = self::createStub(TransactionTransformerInterface::class);

        $transformer = new TransactionsTransformer($transactionTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $transactionTransformer = self::createStub(TransactionTransformerInterface::class);

        $transformer = new TransactionsTransformer($transactionTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(TransactionsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, TransactionsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
