<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Endpoint;

use ChristianBrown\Etsy\Model\ResultSetInterface;
use ChristianBrown\Etsy\Transformer\ObjectsTransformerInterface;

interface ResultSetBasedApiInterface
{
    public const API_PARAM_LIMIT = 'limit';
    public const API_PARAM_OFFSET = 'offset';
    public const DEFAULT_LIMIT = 100;

    public function fetchResultSet(string $urlSprintF, ObjectsTransformerInterface $objectsTransformer, int $offset = 0, int $limit = self::DEFAULT_LIMIT, array $query = []): ResultSetInterface;
}
