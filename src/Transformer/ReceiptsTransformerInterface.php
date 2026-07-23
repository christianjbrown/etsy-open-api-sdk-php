<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ReceiptInterface;

interface ReceiptsTransformerInterface
{
    public const string ARRAY_NAME = 'receipt';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ReceiptInterface>
     */
    public function transform(array $data): array;
}
