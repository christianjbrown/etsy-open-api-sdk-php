<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingVideoInterface;
use ChristianBrown\Etsy\Model\UploadListingVideoRequestInterface;

interface ListingVideoApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/videos';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/videos/%d';
    public const string API_URL_WRITE_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/videos';
    public const string API_URL_WRITE_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/videos/%d';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Deletes a video from a listing.
     */
    public function delete(int $listingId, int $videoId): void;

    /**
     * Reads every video attached to a listing.
     *
     * @return array<int, ListingVideoInterface>
     */
    public function getMultiple(int $listingId, bool $skipCache = false): array;

    public function getOneById(int $listingId, int $videoId, bool $skipCache = false): ListingVideoInterface;

    /**
     * Uploads a new video to a listing, or re-attaches an already-uploaded one.
     */
    public function upload(int $listingId, UploadListingVideoRequestInterface $uploadListingVideoRequest): ListingVideoInterface;
}
