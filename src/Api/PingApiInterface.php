<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\PingInterface;

interface PingApiInterface extends ApiInterface
{
    public const string API_URL = 'https://openapi.etsy.com/v3/application/openapi-ping';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';

    public function ping(): PingInterface;
}
