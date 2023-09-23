<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

final class TransactionsTransformer implements DatasTransformerInterface
{
    private TransactionTransformer $transactionTransformer;

    public function __construct()
    {
        $this->transactionTransformer = new TransactionTransformer();
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
