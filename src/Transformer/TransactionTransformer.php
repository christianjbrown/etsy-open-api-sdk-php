<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\Transaction;

final class TransactionTransformer implements TransactionTransformerInterface
{
    public function transform(array $data): Transaction
    {
        $transaction = new Transaction();
        if (isset($data[self::DATA_KEY_LISTING_ID]) && is_numeric($data[self::DATA_KEY_LISTING_ID])) {
            $transaction->listingId = (int) $data[self::DATA_KEY_LISTING_ID];
        }
        if (isset($data[self::DATA_KEY_QUANTITY]) && is_numeric($data[self::DATA_KEY_QUANTITY])) {
            $transaction->quantity = (int) $data[self::DATA_KEY_QUANTITY];
        }

        // @todo Lots more fields to transform if we need them..

        return $transaction;
    }
}
