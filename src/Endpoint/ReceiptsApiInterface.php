<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Endpoint;

use ChristianBrown\Etsy\Model\ResultSetInterface;

interface ReceiptsApiInterface
{
    public const URL = 'https://openapi.etsy.com/v3/application/shops/%d/receipts';

    public function getResultSet(int $offset = 0, int $limit = ResultSetBasedApiInterface::DEFAULT_LIMIT): ResultSetInterface;
}
