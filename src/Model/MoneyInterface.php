<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface MoneyInterface
{
    public function getAmount(): ?int;

    public function getCurrencyCode(): ?string;

    public function getDivisor(): ?int;

    public function setAmount(?int $value): self;

    public function setCurrencyCode(?string $value): self;

    public function setDivisor(?int $value): self;
}
