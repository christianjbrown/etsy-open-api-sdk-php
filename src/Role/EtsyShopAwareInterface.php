<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Role;

use ChristianBrown\Etsy\Api\ShopApiInterface;
use ChristianBrown\Etsy\Api\ShopHolidayPreferenceApiInterface;
use ChristianBrown\Etsy\Api\ShopProductionPartnerApiInterface;
use ChristianBrown\Etsy\Api\ShopReadinessStateDefinitionApiInterface;
use ChristianBrown\Etsy\Api\ShopReturnPolicyApiInterface;
use ChristianBrown\Etsy\Api\ShopSectionApiInterface;

/**
 * Shop-level configuration: profile, holiday schedule, production partners, readiness states, return policies and sections.
 */
interface EtsyShopAwareInterface
{
    public function getShopApi(): ShopApiInterface;

    public function getShopHolidayPreferenceApi(): ShopHolidayPreferenceApiInterface;

    public function getShopProductionPartnerApi(): ShopProductionPartnerApiInterface;

    public function getShopReadinessStateDefinitionApi(): ShopReadinessStateDefinitionApiInterface;

    public function getShopReturnPolicyApi(): ShopReturnPolicyApiInterface;

    public function getShopSectionApi(): ShopSectionApiInterface;
}
