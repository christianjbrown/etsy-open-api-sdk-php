<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface TransactionInterface extends ObjectInterface
{
    public function getListingId(): ?int;

    public function getQuantity(): ?int;

    public function setListingId(?int $value): self;

    public function setQuantity(?int $value): self;
}
