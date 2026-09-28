<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Host;

final class EtsyHost implements EtsyHostInterface
{
    private string $apiBaseUrl;
    private string $oAuthTokenUrl;

    public function __construct(string $apiBaseUrl = self::PRODUCTION_API_BASE_URL, string $oAuthTokenUrl = self::PRODUCTION_OAUTH_TOKEN_URL)
    {
        $this->apiBaseUrl = $apiBaseUrl;
        $this->oAuthTokenUrl = $oAuthTokenUrl;
    }

    public function getApiBaseUrl(): string
    {
        return $this->apiBaseUrl;
    }

    public function getOAuthTokenUrl(): string
    {
        return $this->oAuthTokenUrl;
    }
}
