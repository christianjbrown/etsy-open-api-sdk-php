<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Request;

interface AuthenticationManagerInterface
{
    public const string HEADER_KEY_API_KEY = 'x-api-key';
    public const string HEADER_KEY_AUTHORIZATION = 'Authorization';
    public const string HEADER_VALUE_AUTHORIZATION_SPRINTF = 'Bearer %s';
    public const string URL_OAUTH_TOKEN_API = 'https://api.etsy.com/v3/public/oauth/token';

    public function getAuthHeaders(bool $forceNew = false): array;
}
