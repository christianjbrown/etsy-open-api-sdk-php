<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Role;

use ChristianBrown\Etsy\Api\UserAddressApiInterface;
use ChristianBrown\Etsy\Api\UserApiInterface;

/**
 * Etsy users and their addresses.
 */
interface EtsyUsersAwareInterface
{
    public function getUserAddressApi(): UserAddressApiInterface;

    public function getUserApi(): UserApiInterface;
}
