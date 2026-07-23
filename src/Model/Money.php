<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Money implements MoneyInterface
{
    private ?int $amount = null;
    private ?string $currencyCode = null;
    private ?int $divisor = null;

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function getDivisor(): ?int
    {
        return $this->divisor;
    }

    public function setAmount(?int $value): MoneyInterface
    {
        $this->amount = $value;

        return $this;
    }

    public function setCurrencyCode(?string $value): MoneyInterface
    {
        $this->currencyCode = $value;

        return $this;
    }

    public function setDivisor(?int $value): MoneyInterface
    {
        $this->divisor = $value;

        return $this;
    }
}
