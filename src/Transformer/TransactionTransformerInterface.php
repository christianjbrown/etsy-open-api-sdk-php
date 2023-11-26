<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\TransactionInterface;

interface TransactionTransformerInterface extends ObjectTransformerInterface
{
    public const DATA_KEY_LISTING_ID = 'listing_id';
    public const DATA_KEY_QUANTITY = 'quantity';

    public function transform(array $data): TransactionInterface;
}
