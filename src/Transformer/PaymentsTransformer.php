<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class PaymentsTransformer implements PaymentsTransformerInterface
{
    private PaymentTransformerInterface $paymentTransformer;

    public function __construct(PaymentTransformerInterface $paymentTransformer)
    {
        $this->paymentTransformer = $paymentTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, PaymentInterface>
     */
    public function transform(array $data): array
    {
        $payments = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $paymentData = $values[$i];
            if (!is_array($paymentData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $payments[] = $this->paymentTransformer->transform($paymentData);
        }

        return $payments;
    }
}
