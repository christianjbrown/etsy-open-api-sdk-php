<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

final class TransactionsTransformer implements TransactionsTransformerInterface
{
    private TransactionTransformerInterface $transactionTransformer;

    public function __construct(TransactionTransformerInterface $transactionTransformer)
    {
        $this->transactionTransformer = $transactionTransformer;
    }

    public function transform(array $data): array
    {
        $transactions = [];
        foreach ($data as $transactionData) {
            $transactions[] = $this->transactionTransformer->transform($transactionData);
        }

        return $transactions;
    }
}
