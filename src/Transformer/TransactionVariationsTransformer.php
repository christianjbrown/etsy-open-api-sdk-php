<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TransactionVariationInterface;

use function array_values;
use function count;
use function sprintf;

final class TransactionVariationsTransformer implements TransactionVariationsTransformerInterface
{
    private TransactionVariationTransformerInterface $transactionVariationTransformer;

    public function __construct(TransactionVariationTransformerInterface $transactionVariationTransformer)
    {
        $this->transactionVariationTransformer = $transactionVariationTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, TransactionVariationInterface>
     */
    public function transform(array $data): array
    {
        $variations = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $variationData = $values[$i];
            if (!is_array($variationData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $variations[] = $this->transactionVariationTransformer->transform($variationData);
        }

        return $variations;
    }
}
