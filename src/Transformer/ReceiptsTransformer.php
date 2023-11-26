<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

final class ReceiptsTransformer implements ReceiptsTransformerInterface
{
    private ReceiptTransformerInterface $receiptTransformer;

    public function __construct(ReceiptTransformerInterface $receiptTransformer)
    {
        $this->receiptTransformer = $receiptTransformer;
    }

    public function transform(array $data): array
    {
        $receipts = [];
        foreach ($data as $receiptData) {
            $receipts[] = $this->receiptTransformer->transform($receiptData);
        }

        return $receipts;
    }
}
