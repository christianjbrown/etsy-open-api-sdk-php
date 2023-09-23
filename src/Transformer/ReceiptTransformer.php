<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\Receipt;

final class ReceiptTransformer implements DataTransformerInterface
{
    private const DATA_KEY_TRANSACTIONS = 'transactions';

    private TransactionsTransformer $transactionsTransformer;

    public function __construct()
    {
        $this->transactionsTransformer = new TransactionsTransformer();
    }

    public function transform(array $data): Receipt
    {
        $receipt = new Receipt();
        if (isset($data[self::DATA_KEY_TRANSACTIONS]) && is_array($data[self::DATA_KEY_TRANSACTIONS])) {
            $receipt->transactions = $this->transactionsTransformer->transform($data[self::DATA_KEY_TRANSACTIONS]);
        }

        // @todo Lots more fields to transform if we need them..

        return $receipt;
    }
}
