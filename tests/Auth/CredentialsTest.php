<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Auth;

use ChristianBrown\Etsy\Auth\Credentials;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\OAuth2Client\Model\AccessTokenInterface;
use ChristianBrown\OAuth2Client\RefreshTokenManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(Credentials::class)]
final class CredentialsTest extends TestCase
{
    public function testToHeaders(): void
    {
        $key = 'test-keystring';
        $sharedSecret = 'test-shared-secret';

        $accessToken = self::createStub(AccessTokenInterface::class);
        $accessToken->method('getAccessToken')->willReturn('test-access-token');

        $refreshTokenManager = self::createMock(RefreshTokenManagerInterface::class);
        $refreshTokenManager->expects(self::once())->method('getAccessToken')
            ->with($key)
            ->willReturn($accessToken);

        $credentials = new Credentials($refreshTokenManager, $key, $sharedSecret);

        $expected = [
            CredentialsInterface::HEADER_KEY_API_KEY => sprintf(CredentialsInterface::API_KEY_HEADER_VALUE_SPRINTF, $key, $sharedSecret),
            CredentialsInterface::HEADER_KEY_AUTHORIZATION => sprintf(CredentialsInterface::AUTHORIZATION_HEADER_VALUE_SPRINTF, 'test-access-token'),
        ];

        self::assertSame($expected, $credentials->toHeaders());
    }
}
