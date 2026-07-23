<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Auth;

use ChristianBrown\OAuth2Client\Model\Exception\RequestExceptionInterface;

interface CredentialsInterface
{
    public const string AUTHORIZATION_HEADER_VALUE_SPRINTF = 'Bearer %s';
    public const string HEADER_KEY_API_KEY = 'x-api-key';
    public const string HEADER_KEY_AUTHORIZATION = 'Authorization';

    /**
     * Builds the two headers every Etsy Open API v3 request needs: the app
     * keystring as `x-api-key`, and a freshly-resolved OAuth2 bearer token as
     * `Authorization`.
     *
     * @throws RequestExceptionInterface
     *
     * @return array<string, string>
     */
    public function toHeaders(): array;
}
