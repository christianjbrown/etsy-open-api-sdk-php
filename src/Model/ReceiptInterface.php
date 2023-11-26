<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ReceiptInterface extends ObjectInterface
{
    public function getTransactions(): ?array;

    public function setTransactions(?array $transactions): self;
}
