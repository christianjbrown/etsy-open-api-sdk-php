<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\Transaction;
use ChristianBrown\Etsy\Model\TransactionInterface;
use InvalidArgumentException;

final class TransactionTransformer implements TransactionTransformerInterface
{
    public function transform(array $data): TransactionInterface
    {
        $transaction = new Transaction();

        foreach ([self::DATA_KEY_LISTING_ID, self::DATA_KEY_QUANTITY] as $key) {
            if (!isset($data[$key])) {
                throw new InvalidArgumentException(sprintf('%s is not set.', $key));
            }
            if (!is_numeric($data[$key])) {
                throw new InvalidArgumentException(sprintf('%s is not numeric.', $key));
            }
        }
        $transaction->setListingId((int) $data[self::DATA_KEY_LISTING_ID]);
        $transaction->setQuantity((int) $data[self::DATA_KEY_QUANTITY]);

        return $transaction;
    }
}
