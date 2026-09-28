<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\PingInterface;
use ChristianBrown\Etsy\Model\ScopesInterface;

interface PingApiInterface
{
    public const string API_URL = 'https://openapi.etsy.com/v3/application/openapi-ping';
    public const string API_URL_SCOPES = 'https://openapi.etsy.com/v3/application/scopes';
    public const string KEY_TOKEN = 'token';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';

    /**
     * Reads the OAuth scopes granted to an access token.
     */
    public function getScopes(string $token): ScopesInterface;

    public function ping(): PingInterface;
}
