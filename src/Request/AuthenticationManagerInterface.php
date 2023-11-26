<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Request;

interface AuthenticationManagerInterface
{
    public const HEADER_KEY_API_KEY = 'x-api-key';
    public const HEADER_KEY_AUTHORIZATION = 'Authorization';
    public const HEADER_VALUE_AUTHORIZATION_SPRINTF = 'Bearer %s';
    public const URL_OAUTH_TOKEN_API = 'https://api.etsy.com/v3/public/oauth/token';

    public function getAuthHeaders(bool $forceNew = false): array;
}
