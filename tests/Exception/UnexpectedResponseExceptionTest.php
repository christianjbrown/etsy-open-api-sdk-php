<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Exception;

use ChristianBrown\Etsy\Exception\ExceptionInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Exception\UnexpectedResponseExceptionInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(UnexpectedResponseException::class)]
final class UnexpectedResponseExceptionTest extends TestCase
{
    public function test(): void
    {
        $exception = new UnexpectedResponseException('test-message');

        self::assertInstanceOf(UnexpectedResponseExceptionInterface::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
        self::assertInstanceOf(RuntimeException::class, $exception);
        self::assertSame('test-message', $exception->getMessage());
    }
}
