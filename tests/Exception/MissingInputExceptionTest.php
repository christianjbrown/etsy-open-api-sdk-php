<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Exception;

use ChristianBrown\Etsy\Exception\ExceptionInterface;
use ChristianBrown\Etsy\Exception\MissingInputException;
use ChristianBrown\Etsy\Exception\MissingInputExceptionInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MissingInputException::class)]
final class MissingInputExceptionTest extends TestCase
{
    public function test(): void
    {
        $exception = new MissingInputException('test-message');

        self::assertInstanceOf(MissingInputExceptionInterface::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
        self::assertInstanceOf(InvalidArgumentException::class, $exception);
        self::assertSame('test-message', $exception->getMessage());
    }
}
