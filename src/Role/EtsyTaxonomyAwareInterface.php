<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Role;

use ChristianBrown\Etsy\Api\BuyerTaxonomyApiInterface;
use ChristianBrown\Etsy\Api\SellerTaxonomyApiInterface;

/**
 * Buyer- and seller-facing taxonomy trees.
 */
interface EtsyTaxonomyAwareInterface
{
    public function getBuyerTaxonomyApi(): BuyerTaxonomyApiInterface;

    public function getSellerTaxonomyApi(): SellerTaxonomyApiInterface;
}
