<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Host;

/**
 * Where the SDK sends requests. `EtsyHost`'s defaults are Etsy's production hosts; pass a
 * differently configured `EtsyHost` (or another implementation) as the `Etsy` facade's last
 * constructor argument to point at Etsy's sandbox (`api.sandbox.etsy.com`,
 * `apiz.sandbox.etsy.com` are not published for this API — see README) or a test double.
 */
interface EtsyHostInterface
{
    public const string PRODUCTION_API_BASE_URL = 'https://openapi.etsy.com';
    public const string PRODUCTION_OAUTH_TOKEN_URL = 'https://api.etsy.com/v3/public/oauth/token';

    /**
     * The host every `API_URL*` constant in `Api/` is rooted at.
     */
    public function getApiBaseUrl(): string;

    /**
     * The OAuth2 token endpoint `RefreshTokenManager` refreshes against.
     */
    public function getOAuthTokenUrl(): string;
}
