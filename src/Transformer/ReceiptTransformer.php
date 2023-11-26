<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\Receipt;
use ChristianBrown\Etsy\Model\ReceiptInterface;

final class ReceiptTransformer implements ReceiptTransformerInterface
{
    private TransactionsTransformerInterface $transactionsTransformer;

    public function __construct(TransactionsTransformerInterface $transactionsTransformer)
    {
        $this->transactionsTransformer = $transactionsTransformer;
    }

    public function transform(array $data): ReceiptInterface
    {
        $receipt = new Receipt();
        if (isset($data[self::DATA_KEY_TRANSACTIONS]) && is_array($data[self::DATA_KEY_TRANSACTIONS])) {
            $transactions = $this->transactionsTransformer->transform($data[self::DATA_KEY_TRANSACTIONS]);
            $receipt->setTransactions($transactions);
        }

        return $receipt;
    }
}
