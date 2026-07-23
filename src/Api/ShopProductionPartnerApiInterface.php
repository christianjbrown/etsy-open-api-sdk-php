<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ShopProductionPartnerInterface;

interface ShopProductionPartnerApiInterface extends ApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/production-partners';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Reads all the production partners for the shop.
     *
     * @return array<int, ShopProductionPartnerInterface>
     */
    public function getMultiple(bool $skipCache = false): array;
}
