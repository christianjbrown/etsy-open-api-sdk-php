<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\UserAddressInterface;

interface UserAddressApiInterface extends ApiInterface
{
    public const string API_URL_MULTIPLE = 'https://openapi.etsy.com/v3/application/user/addresses';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/user/addresses/%d';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Deletes an address from the authenticated user's address book.
     */
    public function delete(int $userAddressId): void;

    /**
     * Reads a single page of the authenticated user's addresses.
     *
     * @return array<int, UserAddressInterface>
     */
    public function getMultiple(int $limit = 25, int $offset = 0, bool $skipCache = false): array;

    public function getOneById(int $userAddressId, bool $skipCache = false): UserAddressInterface;
}
