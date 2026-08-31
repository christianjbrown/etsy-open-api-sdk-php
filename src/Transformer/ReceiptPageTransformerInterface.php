<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ReceiptPageInterface;

interface ReceiptPageTransformerInterface
{
    public const string KEY_COUNT = 'count';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * Turns a `ShopReceipts` envelope into a page carrying the shop's total
     * receipt count alongside the receipts on this page.
     *
     * @param mixed[] $data
     */
    public function transform(array $data): ReceiptPageInterface;
}
