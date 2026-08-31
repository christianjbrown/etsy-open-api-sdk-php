<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ShopSectionInterface;

interface ShopSectionApiInterface extends ApiInterface
{
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/sections';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/sections/%d';
    public const string KEY_RESULTS = 'results';
    public const string KEY_TITLE = 'title';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Creates a new section in the shop.
     */
    public function create(string $title): ShopSectionInterface;

    /**
     * Deletes a section from the shop.
     */
    public function delete(int $shopSectionId): void;

    /**
     * Reads all the sections in the shop.
     *
     * @return array<int, ShopSectionInterface>
     */
    public function getMultiple(bool $skipCache = false): array;

    public function getOneById(int $shopSectionId, bool $skipCache = false): ShopSectionInterface;

    /**
     * Renames a section in the shop.
     */
    public function update(int $shopSectionId, string $title): ShopSectionInterface;
}
