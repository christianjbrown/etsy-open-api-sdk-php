<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Role;

use ChristianBrown\Etsy\Api\ListingBatchApiInterface;
use ChristianBrown\Etsy\Api\ListingFileApiInterface;
use ChristianBrown\Etsy\Api\ListingImageApiInterface;
use ChristianBrown\Etsy\Api\ListingInventoryApiInterface;
use ChristianBrown\Etsy\Api\ListingPersonalizationApiInterface;
use ChristianBrown\Etsy\Api\ListingPropertyApiInterface;
use ChristianBrown\Etsy\Api\ListingTranslationApiInterface;
use ChristianBrown\Etsy\Api\ListingVariationImageApiInterface;
use ChristianBrown\Etsy\Api\ListingVideoApiInterface;
use ChristianBrown\Etsy\Api\ShopListingApiInterface;

/**
 * Listing resources: drafts, media, inventory, personalization, properties and translations.
 */
interface EtsyListingsAwareInterface
{
    public function getListingBatchApi(): ListingBatchApiInterface;

    public function getListingFileApi(): ListingFileApiInterface;

    public function getListingImageApi(): ListingImageApiInterface;

    public function getListingInventoryApi(): ListingInventoryApiInterface;

    public function getListingPersonalizationApi(): ListingPersonalizationApiInterface;

    public function getListingPropertyApi(): ListingPropertyApiInterface;

    public function getListingTranslationApi(): ListingTranslationApiInterface;

    public function getListingVariationImageApi(): ListingVariationImageApiInterface;

    public function getListingVideoApi(): ListingVideoApiInterface;

    public function getShopListingApi(): ShopListingApiInterface;
}
