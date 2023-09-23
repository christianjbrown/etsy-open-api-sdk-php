<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ResultSet;
use ChristianBrown\UserFriendlyException\UserFriendlyException;

final class ResultSetTransformer
{
    private const KEY_COUNT = 'count';
    private const KEY_RESULTS = 'results';

    public function transform(array $data, DatasTransformerInterface $datasTransformer): ResultSet
    {
        if (!isset($data[self::KEY_COUNT]) || !is_numeric($data[self::KEY_COUNT])) {
            throw new UserFriendlyException('Etsy result count is unexpected');
        }
        if (!isset($data[self::KEY_RESULTS]) || !is_array($data[self::KEY_RESULTS])) {
            throw new UserFriendlyException('Etsy result array is unexpected');
        }
        $total = (int) $data[self::KEY_COUNT];
        $results = $datasTransformer->transform($data[self::KEY_RESULTS]);

        return new ResultSet($total, $results);
    }
}
