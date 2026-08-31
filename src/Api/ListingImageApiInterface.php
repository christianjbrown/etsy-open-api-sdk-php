<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingImageInterface;
use ChristianBrown\Etsy\Model\UploadListingImageRequestInterface;

interface ListingImageApiInterface extends ApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/images';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/images/%d';
    public const string API_URL_WRITE_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/images';
    public const string API_URL_WRITE_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/images/%d';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Deletes an image from a listing.
     */
    public function delete(int $listingId, int $listingImageId): void;

    /**
     * Reads every image attached to a listing.
     *
     * @return array<int, ListingImageInterface>
     */
    public function getMultiple(int $listingId, bool $skipCache = false): array;

    public function getOneById(int $listingId, int $listingImageId, bool $skipCache = false): ListingImageInterface;

    /**
     * Uploads a new image to a listing, or re-attaches an already-uploaded one.
     */
    public function upload(int $listingId, UploadListingImageRequestInterface $uploadListingImageRequest): ListingImageInterface;
}
