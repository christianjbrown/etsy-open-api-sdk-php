<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Transaction implements TransactionInterface
{
    private ?int $listingId = null;
    private ?int $quantity = null;

    public function getListingId(): ?int
    {
        return $this->listingId;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setListingId(?int $value): TransactionInterface
    {
        $this->listingId = $value;

        return $this;
    }

    public function setQuantity(?int $value): TransactionInterface
    {
        $this->quantity = $value;

        return $this;
    }
}
