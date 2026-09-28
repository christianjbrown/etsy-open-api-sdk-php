<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\UserInterface;

interface UserApiInterface
{
    public const string API_URL_ME = 'https://openapi.etsy.com/v3/application/users/me';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/users/%d';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';

    public function getById(int $userId, bool $skipCache = false): UserInterface;

    public function getMe(bool $skipCache = false): UserInterface;
}
