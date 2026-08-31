<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Auth;

use ChristianBrown\OAuth2Client\Model\Exception\RequestExceptionInterface;
use ChristianBrown\OAuth2Client\RefreshTokenManagerInterface;

use function sprintf;

final class Credentials implements CredentialsInterface
{
    private string $key;
    private RefreshTokenManagerInterface $refreshTokenManager;
    private string $sharedSecret;

    public function __construct(RefreshTokenManagerInterface $refreshTokenManager, string $key, string $sharedSecret)
    {
        $this->refreshTokenManager = $refreshTokenManager;
        $this->key = $key;
        $this->sharedSecret = $sharedSecret;
    }

    /**
     * @throws RequestExceptionInterface
     *
     * @return array<string, string>
     */
    public function toHeaders(): array
    {
        // The keystring doubles as the OAuth2 client_id; the refresh-token
        // manager returns a cached access token or transparently refreshes it.
        $accessToken = $this->refreshTokenManager->getAccessToken($this->key);

        return [
            self::HEADER_KEY_API_KEY => sprintf(self::API_KEY_HEADER_VALUE_SPRINTF, $this->key, $this->sharedSecret),
            self::HEADER_KEY_AUTHORIZATION => sprintf(self::AUTHORIZATION_HEADER_VALUE_SPRINTF, $accessToken->getAccessToken()),
        ];
    }
}
