<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Model;

use ChristianBrown\Etsy\Model\Transaction;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Transaction::class)]
final class TransactionTest extends TestCase
{
    public function test(): void
    {
        $transaction = new Transaction();
        self::assertNull($transaction->getQuantity());
        self::assertNull($transaction->getListingId());

        self::assertSame($transaction, $transaction->setQuantity(1));
        self::assertSame($transaction, $transaction->setListingId(2));

        self::assertSame(1, $transaction->getQuantity());
        self::assertSame(2, $transaction->getListingId());
    }
}
