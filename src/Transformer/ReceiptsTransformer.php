<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ReceiptInterface;

use function array_values;
use function count;
use function sprintf;

final class ReceiptsTransformer implements ReceiptsTransformerInterface
{
    private ReceiptTransformerInterface $receiptTransformer;

    public function __construct(ReceiptTransformerInterface $receiptTransformer)
    {
        $this->receiptTransformer = $receiptTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ReceiptInterface>
     */
    public function transform(array $data): array
    {
        $receipts = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $receiptData = $values[$i];
            if (!is_array($receiptData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $receipts[] = $this->receiptTransformer->transform($receiptData);
        }

        return $receipts;
    }
}
