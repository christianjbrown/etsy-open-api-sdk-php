<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingPropertyValueInterface;
use ChristianBrown\Etsy\Model\UpdateListingPropertyRequestInterface;

interface ListingPropertyApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/properties';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/properties/%d';
    public const string API_URL_WRITE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/properties/%d';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Deletes a property value from a listing.
     */
    public function delete(int $listingId, int $propertyId): void;

    /**
     * Reads every property value set on a listing.
     *
     * @return array<int, ListingPropertyValueInterface>
     */
    public function getMultiple(int $listingId, bool $skipCache = false): array;

    public function getOneById(int $listingId, int $propertyId, bool $skipCache = false): ListingPropertyValueInterface;

    /**
     * Sets a property value on a listing.
     */
    public function update(int $listingId, int $propertyId, UpdateListingPropertyRequestInterface $updateListingPropertyRequest): ListingPropertyValueInterface;
}
