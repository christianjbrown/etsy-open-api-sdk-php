<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingTranslationInterface;
use ChristianBrown\Etsy\Model\ListingTranslationRequestInterface;

interface ListingTranslationApiInterface
{
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/translations/%s';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';

    /**
     * Creates a translation of a listing's title, description and tags for another language.
     */
    public function create(int $listingId, string $language, ListingTranslationRequestInterface $listingTranslationRequest): ListingTranslationInterface;

    public function getByLanguage(int $listingId, string $language, bool $skipCache = false): ListingTranslationInterface;

    /**
     * Updates an existing translation of a listing's title, description and tags.
     */
    public function update(int $listingId, string $language, ListingTranslationRequestInterface $listingTranslationRequest): ListingTranslationInterface;
}
