<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\CreateDraftListingRequestInterface;
use ChristianBrown\Etsy\Model\ListingInterface;
use ChristianBrown\Etsy\Model\UpdateListingRequestInterface;

interface ShopListingApiInterface
{
    public const string API_URL_ACTIVE = 'https://openapi.etsy.com/v3/application/listings/active';
    public const string API_URL_ACTIVE_BY_SHOP_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/active';
    public const string API_URL_BATCH = 'https://openapi.etsy.com/v3/application/listings/batch';
    public const string API_URL_BY_ID_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d';
    public const string API_URL_BY_RECEIPT_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/receipts/%d/listings';
    public const string API_URL_BY_RETURN_POLICY_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/policies/return/%d/listings';
    public const string API_URL_BY_SHOP_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings';
    public const string API_URL_FEATURED_BY_SHOP_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/featured';
    public const string API_URL_SHOP_SECTIONS_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shop-sections/listings';
    public const string API_URL_UPDATE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d';
    public const string KEY_ALLOW_SUGGESTED_TITLE = 'allow_suggested_title';
    public const string KEY_BUYER_COUNTRY = 'buyer_country';
    public const string KEY_CURRENCY = 'currency';
    public const string KEY_INCLUDES = 'includes';
    public const string KEY_IS_SAFE = 'is_safe';
    public const string KEY_KEYWORDS = 'keywords';
    public const string KEY_LANGUAGE = 'language';
    public const string KEY_LEGACY = 'legacy';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_LISTING_IDS = 'listing_ids';
    public const string KEY_MAX_PRICE = 'max_price';
    public const string KEY_MIN_PRICE = 'min_price';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_RESULTS = 'results';
    public const string KEY_SHOP_LOCATION = 'shop_location';
    public const string KEY_SHOP_SECTION_IDS = 'shop_section_ids';
    public const string KEY_SORT_ON = 'sort_on';
    public const string KEY_SORT_ORDER = 'sort_order';
    public const string KEY_STATE = 'state';
    public const string KEY_TAXONOMY_ID = 'taxonomy_id';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Creates a new draft listing in the shop.
     */
    public function create(CreateDraftListingRequestInterface $createDraftListingRequest): ListingInterface;

    /**
     * Permanently deletes a listing.
     */
    public function delete(int $listingId): void;

    /**
     * Searches active listings across Etsy, optionally filtered by keywords.
     *
     * @return array<int, ListingInterface>
     */
    public function findActive(?string $keywords = null, int $limit = 25, int $offset = 0, bool $skipCache = false, ?string $sortOn = null, ?string $sortOrder = null, ?float $minPrice = null, ?float $maxPrice = null, ?int $taxonomyId = null, ?string $shopLocation = null, ?bool $isSafe = null, ?string $currency = null, ?string $buyerCountry = null): array;

    /**
     * Reads a page of the shop's active listings.
     *
     * @return array<int, ListingInterface>
     */
    public function findActiveByShop(int $limit = 25, int $offset = 0, bool $skipCache = false, ?string $sortOn = null, ?string $sortOrder = null): array;

    public function getById(int $listingId, bool $skipCache = false, ?string $includes = null, ?string $language = null, ?bool $allowSuggestedTitle = null): ListingInterface;

    /**
     * Reads several listings at once by their ids.
     *
     * @param array<int, int> $listingIds
     *
     * @return array<int, ListingInterface>
     */
    public function getByListingIds(array $listingIds, bool $skipCache = false, ?string $includes = null, ?string $currency = null, ?string $buyerCountry = null, ?bool $legacy = null): array;

    /**
     * Reads the listings attached to a receipt.
     *
     * @return array<int, ListingInterface>
     */
    public function getByReceipt(int $receiptId, int $limit = 25, int $offset = 0, bool $skipCache = false, ?bool $legacy = null): array;

    /**
     * Reads the listings governed by a return policy.
     *
     * @return array<int, ListingInterface>
     */
    public function getByReturnPolicy(int $returnPolicyId, bool $skipCache = false, ?bool $legacy = null): array;

    /**
     * Reads a page of the shop's listings, optionally filtered by state.
     *
     * @return array<int, ListingInterface>
     */
    public function getByShop(?string $state = null, int $limit = 25, int $offset = 0, bool $skipCache = false, ?string $sortOn = null, ?string $sortOrder = null, ?string $includes = null): array;

    /**
     * Reads the listings within the given shop sections.
     *
     * @param array<int, int> $shopSectionIds
     *
     * @return array<int, ListingInterface>
     */
    public function getByShopSectionIds(array $shopSectionIds, int $limit = 25, int $offset = 0, bool $skipCache = false, ?string $sortOn = null, ?string $sortOrder = null, ?bool $legacy = null): array;

    /**
     * Reads a page of the shop's featured listings.
     *
     * @return array<int, ListingInterface>
     */
    public function getFeaturedByShop(int $limit = 25, int $offset = 0, bool $skipCache = false, ?bool $legacy = null): array;

    /**
     * Updates a listing's fields. Every field on the request is optional; only what is set changes.
     */
    public function update(int $listingId, UpdateListingRequestInterface $updateListingRequest): ListingInterface;
}
