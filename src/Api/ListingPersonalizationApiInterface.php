<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingPersonalizationInterface;
use ChristianBrown\Etsy\Model\UpdateListingPersonalizationRequestInterface;

interface ListingPersonalizationApiInterface
{
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/personalization';
    public const string API_URL_WRITE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/personalization';
    public const string KEY_SUPPORTS_MULTIPLE_PERSONALIZATION_QUESTIONS = 'supports_multiple_personalization_questions';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';

    /**
     * Removes personalization from a listing.
     */
    public function delete(int $listingId): void;

    public function get(int $listingId, bool $skipCache = false): ListingPersonalizationInterface;

    /**
     * Replaces a listing's personalization questions.
     */
    public function update(int $listingId, UpdateListingPersonalizationRequestInterface $updateListingPersonalizationRequest, bool $supportsMultiplePersonalizationQuestions = false): ListingPersonalizationInterface;
}
