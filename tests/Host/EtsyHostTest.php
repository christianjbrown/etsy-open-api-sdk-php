<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Host;

use ChristianBrown\Etsy\Host\EtsyHost;
use ChristianBrown\Etsy\Host\EtsyHostInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EtsyHost::class)]
final class EtsyHostTest extends TestCase
{
    public function testAcceptsAConfiguredHost(): void
    {
        $host = new EtsyHost('https://api.sandbox.etsy.com', 'https://api.sandbox.etsy.com/v3/public/oauth/token');

        self::assertSame('https://api.sandbox.etsy.com', $host->getApiBaseUrl());
        self::assertSame('https://api.sandbox.etsy.com/v3/public/oauth/token', $host->getOAuthTokenUrl());
    }

    public function testDefaultsToProduction(): void
    {
        $host = new EtsyHost();

        self::assertSame(EtsyHostInterface::PRODUCTION_API_BASE_URL, $host->getApiBaseUrl());
        self::assertSame(EtsyHostInterface::PRODUCTION_OAUTH_TOKEN_URL, $host->getOAuthTokenUrl());
    }
}
