<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingTranslationInterface;

interface ListingTranslationApiInterface extends ApiInterface
{
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/listings/%d/translations/%s';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';

    public function getByLanguage(int $listingId, string $language, bool $skipCache = false): ListingTranslationInterface;
}
