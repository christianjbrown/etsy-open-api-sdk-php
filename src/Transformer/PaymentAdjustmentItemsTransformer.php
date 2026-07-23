<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAdjustmentItemInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class PaymentAdjustmentItemsTransformer implements PaymentAdjustmentItemsTransformerInterface
{
    private PaymentAdjustmentItemTransformerInterface $paymentAdjustmentItemTransformer;

    public function __construct(PaymentAdjustmentItemTransformerInterface $paymentAdjustmentItemTransformer)
    {
        $this->paymentAdjustmentItemTransformer = $paymentAdjustmentItemTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, PaymentAdjustmentItemInterface>
     */
    public function transform(array $data): array
    {
        $items = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $itemData = $values[$i];
            if (!is_array($itemData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $items[] = $this->paymentAdjustmentItemTransformer->transform($itemData);
        }

        return $items;
    }
}
