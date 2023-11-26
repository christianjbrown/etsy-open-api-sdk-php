<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ResultSet;
use ChristianBrown\Etsy\Model\ResultSetInterface;
use InvalidArgumentException;

use function is_numeric;
use function sprintf;

final class ResultSetTransformer implements ResultSetTransformerInterface
{
    public function transform(array $data, ObjectsTransformerInterface $datasTransformer): ResultSetInterface
    {
        if (!isset($data[self::KEY_COUNT]) || !is_numeric($data[self::KEY_COUNT])) {
            throw new InvalidArgumentException(sprintf('ResultSet %d missing or not numeric', self::KEY_COUNT));
        }
        if (!isset($data[self::KEY_RESULTS]) || !is_array($data[self::KEY_RESULTS])) {
            throw new InvalidArgumentException(sprintf('ResultSet %d missing or not an array', self::KEY_RESULTS));
        }
        $total = (int) $data[self::KEY_COUNT];
        $results = $datasTransformer->transform($data[self::KEY_RESULTS]);

        $resultSet = new ResultSet($total, $results);

        return $resultSet;
    }
}
