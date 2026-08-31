<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingVariationImageInterface;
use ChristianBrown\Etsy\Model\UpdateVariationImagesRequestInterface;

interface ListingVariationImageApiInterface extends ApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/variation-images';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Reads the variation images configured for a listing.
     *
     * @return array<int, ListingVariationImageInterface>
     */
    public function getMultiple(int $listingId, bool $skipCache = false): array;

    /**
     * Binds listing variation property values to images.
     *
     * @return array<int, ListingVariationImageInterface>
     */
    public function update(int $listingId, UpdateVariationImagesRequestInterface $updateVariationImagesRequest): array;
}
