<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\RefundInterface;

use function array_values;
use function count;
use function sprintf;

final class RefundsTransformer implements RefundsTransformerInterface
{
    private RefundTransformerInterface $refundTransformer;

    public function __construct(RefundTransformerInterface $refundTransformer)
    {
        $this->refundTransformer = $refundTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, RefundInterface>
     */
    public function transform(array $data): array
    {
        $refunds = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $refundData = $values[$i];
            if (!is_array($refundData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $refunds[] = $this->refundTransformer->transform($refundData);
        }

        return $refunds;
    }
}
