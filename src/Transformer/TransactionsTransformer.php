<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TransactionInterface;

use function array_values;
use function count;
use function sprintf;

final class TransactionsTransformer implements TransactionsTransformerInterface
{
    private TransactionTransformerInterface $transactionTransformer;

    public function __construct(TransactionTransformerInterface $transactionTransformer)
    {
        $this->transactionTransformer = $transactionTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, TransactionInterface>
     */
    public function transform(array $data): array
    {
        $transactions = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $transactionData = $values[$i];
            if (!is_array($transactionData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $transactions[] = $this->transactionTransformer->transform($transactionData);
        }

        return $transactions;
    }
}
