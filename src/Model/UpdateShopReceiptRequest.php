<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateShopReceiptRequest implements UpdateShopReceiptRequestInterface
{
    private ?bool $wasPaid = null;
    private ?bool $wasShipped = null;

    public function getWasPaid(): ?bool
    {
        return $this->wasPaid;
    }

    public function getWasShipped(): ?bool
    {
        return $this->wasShipped;
    }

    public function setWasPaid(?bool $value): UpdateShopReceiptRequestInterface
    {
        $this->wasPaid = $value;

        return $this;
    }

    public function setWasShipped(?bool $value): UpdateShopReceiptRequestInterface
    {
        $this->wasShipped = $value;

        return $this;
    }
}
