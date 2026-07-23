<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Refund implements RefundInterface
{
    private ?MoneyInterface $amount = null;
    private ?int $createdTimestamp = null;
    private ?string $noteFromIssuer = null;
    private ?string $reason = null;
    private ?string $status = null;

    public function getAmount(): ?MoneyInterface
    {
        return $this->amount;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getNoteFromIssuer(): ?string
    {
        return $this->noteFromIssuer;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setAmount(?MoneyInterface $value): RefundInterface
    {
        $this->amount = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): RefundInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setNoteFromIssuer(?string $value): RefundInterface
    {
        $this->noteFromIssuer = $value;

        return $this;
    }

    public function setReason(?string $value): RefundInterface
    {
        $this->reason = $value;

        return $this;
    }

    public function setStatus(?string $value): RefundInterface
    {
        $this->status = $value;

        return $this;
    }
}
