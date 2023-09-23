<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

final class ReceiptsTransformer implements DatasTransformerInterface
{
    private ReceiptTransformer $receiptTransformer;

    public function __construct()
    {
        $this->receiptTransformer = new ReceiptTransformer();
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
