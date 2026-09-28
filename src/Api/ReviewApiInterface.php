<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ReviewInterface;

interface ReviewApiInterface
{
    public const string API_URL_BY_LISTING_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/reviews';
    public const string API_URL_BY_SHOP_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/reviews';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_MAX_CREATED = 'max_created';
    public const string KEY_MIN_CREATED = 'min_created';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Reads a single page of the reviews left for a listing.
     *
     * @return array<int, ReviewInterface>
     */
    public function getByListing(int $listingId, int $limit = 25, int $offset = 0, bool $skipCache = false): array;

    /**
     * Reads a single page of the reviews left for the shop.
     *
     * @return array<int, ReviewInterface>
     */
    public function getByShop(?int $minCreated = null, ?int $maxCreated = null, int $limit = 25, int $offset = 0, bool $skipCache = false): array;
}
