<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Model;

use ChristianBrown\Etsy\Model\Receipt;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Receipt::class)]
final class ReceiptTest extends TestCase
{
    public function test(): void
    {
        $receipt = new Receipt();
        self::assertNull($receipt->getTransactions());
        self::assertSame($receipt, $receipt->setTransactions(['data-transactions']));
        self::assertSame(['data-transactions'], $receipt->getTransactions());
    }
}
