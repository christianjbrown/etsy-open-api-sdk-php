<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ReceiptPage implements ReceiptPageInterface
{
    private int $count;

    /**
     * @var array<int, ReceiptInterface>
     */
    private array $receipts = [];

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return array<int, ReceiptInterface>
     */
    public function getReceipts(): array
    {
        return $this->receipts;
    }

    public function setCount(int $value): ReceiptPageInterface
    {
        $this->count = $value;

        return $this;
    }

    /**
     * @param array<int, ReceiptInterface> $value
     */
    public function setReceipts(array $value): ReceiptPageInterface
    {
        $this->receipts = $value;

        return $this;
    }
}
