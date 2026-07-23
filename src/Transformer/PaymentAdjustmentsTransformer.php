<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAdjustmentInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class PaymentAdjustmentsTransformer implements PaymentAdjustmentsTransformerInterface
{
    private PaymentAdjustmentTransformerInterface $paymentAdjustmentTransformer;

    public function __construct(PaymentAdjustmentTransformerInterface $paymentAdjustmentTransformer)
    {
        $this->paymentAdjustmentTransformer = $paymentAdjustmentTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, PaymentAdjustmentInterface>
     */
    public function transform(array $data): array
    {
        $adjustments = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $adjustmentData = $values[$i];
            if (!is_array($adjustmentData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $adjustments[] = $this->paymentAdjustmentTransformer->transform($adjustmentData);
        }

        return $adjustments;
    }
}
