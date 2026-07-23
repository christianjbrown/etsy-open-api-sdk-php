<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntryInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class PaymentAccountLedgerEntriesTransformer implements PaymentAccountLedgerEntriesTransformerInterface
{
    private PaymentAccountLedgerEntryTransformerInterface $paymentAccountLedgerEntryTransformer;

    public function __construct(PaymentAccountLedgerEntryTransformerInterface $paymentAccountLedgerEntryTransformer)
    {
        $this->paymentAccountLedgerEntryTransformer = $paymentAccountLedgerEntryTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, PaymentAccountLedgerEntryInterface>
     */
    public function transform(array $data): array
    {
        $entries = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $entryData = $values[$i];
            if (!is_array($entryData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $entries[] = $this->paymentAccountLedgerEntryTransformer->transform($entryData);
        }

        return $entries;
    }
}
