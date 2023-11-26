<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ResultSetInterface;

interface ResultSetTransformerInterface
{
    public const KEY_COUNT = 'count';
    public const KEY_RESULTS = 'results';

    public function transform(array $data, ObjectsTransformerInterface $datasTransformer): ResultSetInterface;
}
