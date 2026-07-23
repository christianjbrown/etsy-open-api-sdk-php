<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ListingPersonalizationInterface;

interface ListingPersonalizationApiInterface extends ApiInterface
{
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/listings/%d/personalization';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';

    public function get(int $listingId, bool $skipCache = false): ListingPersonalizationInterface;
}
