<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `updateShopReceipt` call.
 */
interface UpdateShopReceiptRequestInterface
{
    public function getWasPaid(): ?bool;

    public function getWasShipped(): ?bool;

    public function setWasPaid(?bool $value): self;

    public function setWasShipped(?bool $value): self;
}
