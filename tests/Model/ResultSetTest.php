<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Model;

use ChristianBrown\Etsy\Model\ResultSet;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResultSet::class)]
final class ResultSetTest extends TestCase
{
    public function test(): void
    {
        $resultSet = new ResultSet(42, ['data-result-1', 'data-result-2']);
        self::assertSame(42, $resultSet->getTotal());
        self::assertSame(['data-result-1', 'data-result-2'], $resultSet->getResults());
        self::assertSame(2, $resultSet->getCount());
    }
}
