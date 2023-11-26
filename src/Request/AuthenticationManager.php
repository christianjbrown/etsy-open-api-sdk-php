<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Request;

use ChristianBrown\Oauth2Client\RefreshTokenManagerInterface;

use function sprintf;

final class AuthenticationManager implements AuthenticationManagerInterface
{
    private string $key;
    private RefreshTokenManagerInterface $refreshTokenManager;

    public function __construct(RefreshTokenManagerInterface $refreshTokenManager, string $key)
    {
        $this->refreshTokenManager = $refreshTokenManager;
        $this->key = $key;
    }

    public function getAuthHeaders(bool $forceNew = false): array
    {
        $token = $this->refreshTokenManager->getAccessToken($this->key, $forceNew);
        $accessTokenValue = $token->getAccessToken();

        $headers = [
            self::HEADER_KEY_API_KEY => $this->key,
            self::HEADER_KEY_AUTHORIZATION => sprintf(self::HEADER_VALUE_AUTHORIZATION_SPRINTF, $accessTokenValue),
        ];

        return $headers;
    }
}
