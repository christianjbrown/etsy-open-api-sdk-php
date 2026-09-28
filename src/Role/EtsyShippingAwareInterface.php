<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Role;

use ChristianBrown\Etsy\Api\ShippingProfileApiInterface;

/**
 * Shipping profiles.
 */
interface EtsyShippingAwareInterface
{
    public function getShippingProfileApi(): ShippingProfileApiInterface;
}
