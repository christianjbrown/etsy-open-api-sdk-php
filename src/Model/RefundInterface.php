<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface RefundInterface
{
    public function getAmount(): ?MoneyInterface;

    public function getCreatedTimestamp(): ?int;

    public function getNoteFromIssuer(): ?string;

    public function getReason(): ?string;

    public function getStatus(): ?string;

    public function setAmount(?MoneyInterface $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setNoteFromIssuer(?string $value): self;

    public function setReason(?string $value): self;

    public function setStatus(?string $value): self;
}
