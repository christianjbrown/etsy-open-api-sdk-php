<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ShopInterface;

interface ShopApiInterface extends ApiInterface
{
    public const string API_URL_BY_OWNER_SPRINTF = 'https://openapi.etsy.com/v3/application/users/%d/shops';
    public const string API_URL_FIND = 'https://openapi.etsy.com/v3/application/shops';
    public const string API_URL_SHOP_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_RESULTS = 'results';
    public const string KEY_SHOP_NAME = 'shop_name';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Searches shops whose names match the given string.
     *
     * @return array<int, ShopInterface>
     */
    public function findByName(string $shopName, int $limit = 25, int $offset = 0, bool $skipCache = false): array;

    public function getByOwnerUserId(int $userId, bool $skipCache = false): ShopInterface;

    public function getShop(bool $skipCache = false): ShopInterface;
}
