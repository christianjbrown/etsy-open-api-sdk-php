<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ResultSetInterface;

interface ApiInterface
{
    public const DEFAULT_LIMIT = 100;

    public function get(int $offset = 0, int $limit = self::DEFAULT_LIMIT): ResultSetInterface;
}
