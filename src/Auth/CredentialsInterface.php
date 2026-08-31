<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Auth;

use ChristianBrown\OAuth2Client\Model\Exception\RequestExceptionInterface;

interface CredentialsInterface
{
    public const string API_KEY_HEADER_VALUE_SPRINTF = '%s:%s';
    public const string AUTHORIZATION_HEADER_VALUE_SPRINTF = 'Bearer %s';
    public const string HEADER_KEY_API_KEY = 'x-api-key';
    public const string HEADER_KEY_AUTHORIZATION = 'Authorization';

    /**
     * Builds the two headers every Etsy Open API v3 request needs.
     *
     * `x-api-key` carries the app keystring and its shared secret joined by a
     * colon. Etsy rejects the keystring on its own with "Shared secret is
     * required in x-api-key header." `Authorization` carries a freshly-resolved
     * OAuth2 bearer token.
     *
     * @throws RequestExceptionInterface
     *
     * @return array<string, string>
     */
    public function toHeaders(): array;
}
