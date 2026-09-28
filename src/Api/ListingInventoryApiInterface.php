<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingInventoryInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;
use ChristianBrown\Etsy\Model\UpdateListingInventoryRequestInterface;

interface ListingInventoryApiInterface
{
    public const string API_URL_BY_LISTING_ID_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/inventory';
    public const string API_URL_OFFERING_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/products/%d/offerings/%d';
    public const string API_URL_PRODUCT_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/inventory/products/%d';
    public const string KEY_MAX_VARIATIONS_SUPPORTED = 'max_variations_supported';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';

    public function getByListingId(int $listingId, bool $skipCache = false): ListingInventoryInterface;

    public function getOffering(int $listingId, int $productId, int $offeringId, bool $skipCache = false): ListingInventoryProductOfferingInterface;

    public function getProduct(int $listingId, int $productId, bool $skipCache = false): ListingInventoryProductInterface;

    /**
     * Replaces the listing's whole inventory (products, offerings and variation structure).
     */
    public function update(int $listingId, UpdateListingInventoryRequestInterface $updateListingInventoryRequest, ?string $maxVariationsSupported = null): ListingInventoryInterface;
}
