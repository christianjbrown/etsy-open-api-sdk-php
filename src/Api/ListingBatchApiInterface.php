<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

interface ListingBatchApiInterface
{
    public const string API_URL_INVENTORY = 'https://openapi.etsy.com/v3/application/listings/batch/inventory';
    public const string API_URL_SHIPPING = 'https://openapi.etsy.com/v3/application/listings/batch/shipping';
    public const string KEY_LISTING_IDS = 'listing_ids';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Reads several listings with their inventory associations at once by their ids.
     *
     * @param array<int, int> $listingIds
     *
     * @return array<int, ListingWithAssociationsInterface>
     */
    public function getInventoryByListingIds(array $listingIds, bool $skipCache = false): array;

    /**
     * Reads several listings with their shipping associations at once by their ids.
     *
     * @param array<int, int> $listingIds
     *
     * @return array<int, ListingWithAssociationsInterface>
     */
    public function getShippingByListingIds(array $listingIds, bool $skipCache = false): array;
}
