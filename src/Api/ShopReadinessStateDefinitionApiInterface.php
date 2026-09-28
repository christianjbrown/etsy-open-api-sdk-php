<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\CreateShopReadinessStateDefinitionRequestInterface;
use ChristianBrown\Etsy\Model\ShopReadinessStateDefinitionInterface;
use ChristianBrown\Etsy\Model\UpdateShopReadinessStateDefinitionRequestInterface;

interface ShopReadinessStateDefinitionApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/readiness-state-definitions';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/readiness-state-definitions/%d';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Creates a new readiness state (processing profile) definition for the shop.
     */
    public function create(CreateShopReadinessStateDefinitionRequestInterface $createShopReadinessStateDefinitionRequest): ShopReadinessStateDefinitionInterface;

    /**
     * Deletes a readiness state definition from the shop.
     */
    public function delete(int $readinessStateDefinitionId): void;

    /**
     * Reads all the readiness state definitions for the shop.
     *
     * @return array<int, ShopReadinessStateDefinitionInterface>
     */
    public function getMultiple(bool $skipCache = false, ?int $limit = null, ?int $offset = null): array;

    public function getOneById(int $readinessStateDefinitionId, bool $skipCache = false): ShopReadinessStateDefinitionInterface;

    /**
     * Updates an existing readiness state definition for the shop.
     */
    public function update(int $readinessStateDefinitionId, UpdateShopReadinessStateDefinitionRequestInterface $updateShopReadinessStateDefinitionRequest): ShopReadinessStateDefinitionInterface;
}
