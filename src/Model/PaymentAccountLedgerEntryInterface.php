<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface PaymentAccountLedgerEntryInterface
{
    public function getAmount(): ?int;

    public function getBalance(): ?int;

    public function getCreateDate(): ?int;

    public function getCreatedTimestamp(): ?int;

    public function getCurrency(): ?string;

    public function getDescription(): ?string;

    public function getEntryId(): int;

    public function getLedgerId(): ?int;

    public function getLedgerType(): ?string;

    public function getParentEntryId(): ?int;

    /**
     * @return array<int, PaymentAdjustmentInterface>
     */
    public function getPaymentAdjustments(): array;

    public function getReferenceId(): ?string;

    public function getReferenceType(): ?string;

    public function getSequenceNumber(): ?int;

    public function setAmount(?int $value): self;

    public function setBalance(?int $value): self;

    public function setCreateDate(?int $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setCurrency(?string $value): self;

    public function setDescription(?string $value): self;

    public function setEntryId(int $value): self;

    public function setLedgerId(?int $value): self;

    public function setLedgerType(?string $value): self;

    public function setParentEntryId(?int $value): self;

    /**
     * @param array<int, PaymentAdjustmentInterface> $value
     */
    public function setPaymentAdjustments(array $value): self;

    public function setReferenceId(?string $value): self;

    public function setReferenceType(?string $value): self;

    public function setSequenceNumber(?int $value): self;
}
