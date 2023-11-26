<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Receipt implements ReceiptInterface
{
    private ?array $transactions = null;

    public function getTransactions(): ?array
    {
        return $this->transactions;
    }

    public function setTransactions(?array $transactions): ReceiptInterface
    {
        $this->transactions = $transactions;

        return $this;
    }
}
