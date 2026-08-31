<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ReceiptPageInterface
{
    /**
     * The total number of receipts the shop has, not the number on this page.
     * Divide it by the page size to drive a paging loop.
     */
    public function getCount(): int;

    /**
     * @return array<int, ReceiptInterface>
     */
    public function getReceipts(): array;

    public function setCount(int $value): self;

    /**
     * @param array<int, ReceiptInterface> $value
     */
    public function setReceipts(array $value): self;
}
