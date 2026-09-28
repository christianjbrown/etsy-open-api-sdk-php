<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingFileInterface;
use ChristianBrown\Etsy\Model\UploadListingFileRequestInterface;

interface ListingFileApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/files';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/files/%d';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Deletes a file from a digital listing.
     */
    public function delete(int $listingId, int $listingFileId): void;

    /**
     * Reads every file attached to a listing.
     *
     * @return array<int, ListingFileInterface>
     */
    public function getMultiple(int $listingId, bool $skipCache = false): array;

    public function getOneById(int $listingId, int $listingFileId, bool $skipCache = false): ListingFileInterface;

    /**
     * Uploads a new digital file to a listing, or re-attaches an already-uploaded one.
     */
    public function upload(int $listingId, UploadListingFileRequestInterface $uploadListingFileRequest): ListingFileInterface;
}
