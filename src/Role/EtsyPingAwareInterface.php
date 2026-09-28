<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Role;

use ChristianBrown\Etsy\Api\PingApiInterface;

/**
 * The OAuth ping/health check and token scope introspection endpoints.
 */
interface EtsyPingAwareInterface
{
    public function getPingApi(): PingApiInterface;
}
