<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Request;

use ChristianBrown\Etsy\Request\AuthenticationManager;
use ChristianBrown\Etsy\Request\AuthenticationManagerInterface;
use ChristianBrown\OAuth2Client\Model\AccessTokenInterface;
use ChristianBrown\OAuth2Client\RefreshTokenManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(AuthenticationManager::class)]
final class AuthenticationManagerTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[TestWith([true])]
    #[TestWith([false])]
    public function test(bool $forceNew): void
    {
        $accessToken = $this->createMock(AccessTokenInterface::class);
        $accessToken->method('getAccessToken')
            ->willReturn('test-access-token');

        $refreshTokenManager = $this->createMock(RefreshTokenManagerInterface::class);
        $refreshTokenManager->method('getAccessToken')
            ->with('test-key', $forceNew)
            ->willReturn($accessToken);

        $expected = [
            AuthenticationManagerInterface::HEADER_KEY_API_KEY => 'test-key',
            AuthenticationManagerInterface::HEADER_KEY_AUTHORIZATION => sprintf(AuthenticationManagerInterface::HEADER_VALUE_AUTHORIZATION_SPRINTF, 'test-access-token'),
        ];

        $manager = new AuthenticationManager($refreshTokenManager, 'test-key');
        $actual = $manager->getAuthHeaders($forceNew);

        self::assertSame($expected, $actual);
    }
}
