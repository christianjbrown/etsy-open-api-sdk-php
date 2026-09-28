<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;
use ChristianBrown\Etsy\Model\ShopReturnPolicyRequestInterface;

interface ShopReturnPolicyApiInterface
{
    public const string API_URL_CONSOLIDATE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/policies/return/consolidate';
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/policies/return';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/policies/return/%d';
    public const string KEY_DESTINATION_RETURN_POLICY_ID = 'destination_return_policy_id';
    public const string KEY_RESULTS = 'results';
    public const string KEY_SOURCE_RETURN_POLICY_ID = 'source_return_policy_id';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Merges the listings on the source policy onto the destination policy and deletes the source.
     */
    public function consolidate(int $sourceReturnPolicyId, int $destinationReturnPolicyId): ShopReturnPolicyInterface;

    /**
     * Creates a new return policy for the shop.
     */
    public function create(ShopReturnPolicyRequestInterface $shopReturnPolicyRequest): ShopReturnPolicyInterface;

    /**
     * Deletes a return policy from the shop.
     */
    public function delete(int $returnPolicyId): void;

    /**
     * Reads all the listing-level return policies for the shop.
     *
     * @return array<int, ShopReturnPolicyInterface>
     */
    public function getMultiple(bool $skipCache = false): array;

    public function getOneById(int $returnPolicyId, bool $skipCache = false): ShopReturnPolicyInterface;

    /**
     * Updates an existing return policy for the shop.
     */
    public function update(int $returnPolicyId, ShopReturnPolicyRequestInterface $shopReturnPolicyRequest): ShopReturnPolicyInterface;
}
