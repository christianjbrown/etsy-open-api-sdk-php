<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\Receipt;

interface ReceiptTransformerInterface extends DataTransformerInterface
{
    public const DATA_KEY_TRANSACTIONS = 'transactions';

    public function transform(array $data): Receipt;
}
