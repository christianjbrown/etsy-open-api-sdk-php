<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ReceiptInterface;

interface ReceiptTransformerInterface extends ObjectTransformerInterface
{
    public const string DATA_KEY_TRANSACTIONS = 'transactions';

    public function transform(array $data): ReceiptInterface;
}
