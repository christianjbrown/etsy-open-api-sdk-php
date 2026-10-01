<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

use function dirname;
use function escapeshellarg;
use function exec;
use function implode;
use function sprintf;

use const PHP_BINARY;

/**
 * Builds the real object graph through the factory and resolves every resource client, so a
 * missing or mistyped service registration fails here rather than in a consumer's application.
 *
 * The check runs in a child PHP process with coverage off. It only constructs objects, and
 * constructing them inside a parallel test worker makes the merged coverage report understate
 * the unit tests of the classes it builds.
 */
#[CoversNothing]
final class EtsyWiringTest extends TestCase
{
    public function testEveryResourceClientResolves(): void
    {
        $script = dirname(__DIR__).'/tests/Fixtures/resolve-every-client.php';
        $output = [];
        $exitCode = 0;

        exec(sprintf('XDEBUG_MODE=off %s %s 2>&1', escapeshellarg(PHP_BINARY), escapeshellarg($script)), $output, $exitCode);

        self::assertSame(0, $exitCode, implode("\n", $output));
        self::assertSame('resolved 27 clients', $output[0] ?? '');
    }
}
