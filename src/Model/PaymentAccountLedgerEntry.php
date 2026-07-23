<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class PaymentAccountLedgerEntry implements PaymentAccountLedgerEntryInterface
{
    private ?int $amount = null;
    private ?int $balance = null;
    private ?int $createDate = null;
    private ?int $createdTimestamp = null;
    private ?string $currency = null;
    private ?string $description = null;
    private int $entryId;
    private ?int $ledgerId = null;
    private ?string $ledgerType = null;
    private ?int $parentEntryId = null;

    /**
     * @var array<int, PaymentAdjustmentInterface>
     */
    private array $paymentAdjustments = [];
    private ?string $referenceId = null;
    private ?string $referenceType = null;
    private ?int $sequenceNumber = null;

    public function __construct(int $entryId)
    {
        $this->entryId = $entryId;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function getCreateDate(): ?int
    {
        return $this->createDate;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEntryId(): int
    {
        return $this->entryId;
    }

    public function getLedgerId(): ?int
    {
        return $this->ledgerId;
    }

    public function getLedgerType(): ?string
    {
        return $this->ledgerType;
    }

    public function getParentEntryId(): ?int
    {
        return $this->parentEntryId;
    }

    /**
     * @return array<int, PaymentAdjustmentInterface>
     */
    public function getPaymentAdjustments(): array
    {
        return $this->paymentAdjustments;
    }

    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }

    public function getReferenceType(): ?string
    {
        return $this->referenceType;
    }

    public function getSequenceNumber(): ?int
    {
        return $this->sequenceNumber;
    }

    public function setAmount(?int $value): PaymentAccountLedgerEntryInterface
    {
        $this->amount = $value;

        return $this;
    }

    public function setBalance(?int $value): PaymentAccountLedgerEntryInterface
    {
        $this->balance = $value;

        return $this;
    }

    public function setCreateDate(?int $value): PaymentAccountLedgerEntryInterface
    {
        $this->createDate = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): PaymentAccountLedgerEntryInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCurrency(?string $value): PaymentAccountLedgerEntryInterface
    {
        $this->currency = $value;

        return $this;
    }

    public function setDescription(?string $value): PaymentAccountLedgerEntryInterface
    {
        $this->description = $value;

        return $this;
    }

    public function setEntryId(int $value): PaymentAccountLedgerEntryInterface
    {
        $this->entryId = $value;

        return $this;
    }

    public function setLedgerId(?int $value): PaymentAccountLedgerEntryInterface
    {
        $this->ledgerId = $value;

        return $this;
    }

    public function setLedgerType(?string $value): PaymentAccountLedgerEntryInterface
    {
        $this->ledgerType = $value;

        return $this;
    }

    public function setParentEntryId(?int $value): PaymentAccountLedgerEntryInterface
    {
        $this->parentEntryId = $value;

        return $this;
    }

    /**
     * @param array<int, PaymentAdjustmentInterface> $value
     */
    public function setPaymentAdjustments(array $value): PaymentAccountLedgerEntryInterface
    {
        $this->paymentAdjustments = $value;

        return $this;
    }

    public function setReferenceId(?string $value): PaymentAccountLedgerEntryInterface
    {
        $this->referenceId = $value;

        return $this;
    }

    public function setReferenceType(?string $value): PaymentAccountLedgerEntryInterface
    {
        $this->referenceType = $value;

        return $this;
    }

    public function setSequenceNumber(?int $value): PaymentAccountLedgerEntryInterface
    {
        $this->sequenceNumber = $value;

        return $this;
    }
}
