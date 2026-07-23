<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class PaymentAdjustmentItem implements PaymentAdjustmentItemInterface
{
    private ?string $adjustmentType = null;
    private ?int $amount = null;
    private ?int $billPaymentId = null;
    private ?int $createdTimestamp = null;
    private ?int $paymentAdjustmentId = null;
    private ?int $paymentAdjustmentItemId = null;
    private ?int $shopAmount = null;
    private ?int $transactionId = null;
    private ?int $updatedTimestamp = null;

    public function getAdjustmentType(): ?string
    {
        return $this->adjustmentType;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function getBillPaymentId(): ?int
    {
        return $this->billPaymentId;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getPaymentAdjustmentId(): ?int
    {
        return $this->paymentAdjustmentId;
    }

    public function getPaymentAdjustmentItemId(): ?int
    {
        return $this->paymentAdjustmentItemId;
    }

    public function getShopAmount(): ?int
    {
        return $this->shopAmount;
    }

    public function getTransactionId(): ?int
    {
        return $this->transactionId;
    }

    public function getUpdatedTimestamp(): ?int
    {
        return $this->updatedTimestamp;
    }

    public function setAdjustmentType(?string $value): PaymentAdjustmentItemInterface
    {
        $this->adjustmentType = $value;

        return $this;
    }

    public function setAmount(?int $value): PaymentAdjustmentItemInterface
    {
        $this->amount = $value;

        return $this;
    }

    public function setBillPaymentId(?int $value): PaymentAdjustmentItemInterface
    {
        $this->billPaymentId = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): PaymentAdjustmentItemInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setPaymentAdjustmentId(?int $value): PaymentAdjustmentItemInterface
    {
        $this->paymentAdjustmentId = $value;

        return $this;
    }

    public function setPaymentAdjustmentItemId(?int $value): PaymentAdjustmentItemInterface
    {
        $this->paymentAdjustmentItemId = $value;

        return $this;
    }

    public function setShopAmount(?int $value): PaymentAdjustmentItemInterface
    {
        $this->shopAmount = $value;

        return $this;
    }

    public function setTransactionId(?int $value): PaymentAdjustmentItemInterface
    {
        $this->transactionId = $value;

        return $this;
    }

    public function setUpdatedTimestamp(?int $value): PaymentAdjustmentItemInterface
    {
        $this->updatedTimestamp = $value;

        return $this;
    }
}
