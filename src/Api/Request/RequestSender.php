<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api\Request;

use ChristianBrown\JsonApiClient\RequestSender as BaseRequestSender;
use ChristianBrown\JsonApiClient\RequestSenderInterface as BaseRequestSenderInterface;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\Oauth2Client\RefreshTokenManager;
use ChristianBrown\Oauth2Client\RefreshTokenManagerInterface;

final class RequestSender implements RequestSenderInterface
{
    private string $key;
    private RefreshTokenManagerInterface $refreshTokenManager;
    private BaseRequestSenderInterface $requestSender;

    public function __construct(string $key, KeyValueStoreInterface $refreshTokenKeyValueStore, ?KeyValueStoreInterface $accessTokenKeyValueStore = null)
    {
        $this->key = $key;
        $this->refreshTokenManager = new RefreshTokenManager(self::URL_OAUTH_TOKEN_API, self::FRIENDLY_NAME, $refreshTokenKeyValueStore, $accessTokenKeyValueStore);
        $this->requestSender = new BaseRequestSender(new BadResponseTransformer());
    }

    public function get(string $url, array $queryStrings = []): array
    {
        $headers = $this->getHeaders();
        $data = $this->requestSender->get(self::FRIENDLY_NAME, $url, $queryStrings, $headers);

        return $data;
    }

    private function getHeaders(): array
    {
        $token = $this->refreshTokenManager->getAccessToken($this->key);
        $accessToken = $token->getRefreshToken();

        $headers = [
            self::HEADER_KEY_API_KEY => $this->key,
            self::HEADER_KEY_AUTHORIZATION => sprintf(self::HEADER_VALUE_AUTHORIZATION_SPRINTF, $accessToken),
        ];

        return $headers;
    }
}
