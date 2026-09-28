<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Cache;

use ChristianBrown\Etsy\Cache\ResponseCache;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResponseCache::class)]
final class ResponseCacheTest extends TestCase
{
    public function testClearDropsEveryEntry(): void
    {
        $cache = new ResponseCache();
        $cache->set('a', 1);
        $cache->set('b', 2);

        $cache->clear();

        self::assertFalse($cache->has('a'));
        self::assertFalse($cache->has('b'));
    }

    public function testDeleteDropsOneEntry(): void
    {
        $cache = new ResponseCache();
        $cache->set('a', 1);
        $cache->set('b', 2);

        $cache->delete('a');

        self::assertFalse($cache->has('a'));
        self::assertTrue($cache->has('b'));
    }

    public function testDeleteOfAMissingKeyIsANoOp(): void
    {
        $cache = new ResponseCache();

        $cache->delete('missing');

        self::assertFalse($cache->has('missing'));
    }

    public function testGetReturnsNullForAMissingKey(): void
    {
        $cache = new ResponseCache();

        self::assertNull($cache->get('missing'));
    }

    public function testHasIsFalseForAMissingKey(): void
    {
        $cache = new ResponseCache();

        self::assertFalse($cache->has('missing'));
    }

    public function testSetThenGetReturnsTheStoredValue(): void
    {
        $cache = new ResponseCache();

        $cache->set('a', ['one']);

        self::assertTrue($cache->has('a'));
        self::assertSame(['one'], $cache->get('a'));
    }
}
