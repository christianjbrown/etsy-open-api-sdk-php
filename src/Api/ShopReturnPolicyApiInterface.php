<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;

interface ShopReturnPolicyApiInterface extends ApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/policies/return';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/policies/return/%d';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Reads all the listing-level return policies for the shop.
     *
     * @return array<int, ShopReturnPolicyInterface>
     */
    public function getMultiple(bool $skipCache = false): array;

    public function getOneById(int $returnPolicyId, bool $skipCache = false): ShopReturnPolicyInterface;
}
