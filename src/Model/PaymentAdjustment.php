<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class PaymentAdjustment implements PaymentAdjustmentInterface
{
    private ?int $buyerTotalAdjustmentAmount = null;
    private ?int $createdTimestamp = null;
    private ?int $createTimestamp = null;
    private ?bool $isSuccess = null;
    private int $paymentAdjustmentId;

    /**
     * @var array<int, PaymentAdjustmentItemInterface>
     */
    private array $paymentAdjustmentItems = [];
    private ?int $paymentId = null;
    private ?string $reasonCode = null;
    private ?int $shopTotalAdjustmentAmount = null;
    private ?string $status = null;
    private ?int $totalAdjustmentAmount = null;
    private ?int $totalFeeAdjustmentAmount = null;
    private ?int $updatedTimestamp = null;
    private ?int $updateTimestamp = null;
    private ?int $userId = null;

    public function __construct(int $paymentAdjustmentId)
    {
        $this->paymentAdjustmentId = $paymentAdjustmentId;
    }

    public function getBuyerTotalAdjustmentAmount(): ?int
    {
        return $this->buyerTotalAdjustmentAmount;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getCreateTimestamp(): ?int
    {
        return $this->createTimestamp;
    }

    public function getIsSuccess(): ?bool
    {
        return $this->isSuccess;
    }

    public function getPaymentAdjustmentId(): int
    {
        return $this->paymentAdjustmentId;
    }

    /**
     * @return array<int, PaymentAdjustmentItemInterface>
     */
    public function getPaymentAdjustmentItems(): array
    {
        return $this->paymentAdjustmentItems;
    }

    public function getPaymentId(): ?int
    {
        return $this->paymentId;
    }

    public function getReasonCode(): ?string
    {
        return $this->reasonCode;
    }

    public function getShopTotalAdjustmentAmount(): ?int
    {
        return $this->shopTotalAdjustmentAmount;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getTotalAdjustmentAmount(): ?int
    {
        return $this->totalAdjustmentAmount;
    }

    public function getTotalFeeAdjustmentAmount(): ?int
    {
        return $this->totalFeeAdjustmentAmount;
    }

    public function getUpdatedTimestamp(): ?int
    {
        return $this->updatedTimestamp;
    }

    public function getUpdateTimestamp(): ?int
    {
        return $this->updateTimestamp;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setBuyerTotalAdjustmentAmount(?int $value): PaymentAdjustmentInterface
    {
        $this->buyerTotalAdjustmentAmount = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): PaymentAdjustmentInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCreateTimestamp(?int $value): PaymentAdjustmentInterface
    {
        $this->createTimestamp = $value;

        return $this;
    }

    public function setIsSuccess(?bool $value): PaymentAdjustmentInterface
    {
        $this->isSuccess = $value;

        return $this;
    }

    public function setPaymentAdjustmentId(int $value): PaymentAdjustmentInterface
    {
        $this->paymentAdjustmentId = $value;

        return $this;
    }

    /**
     * @param array<int, PaymentAdjustmentItemInterface> $value
     */
    public function setPaymentAdjustmentItems(array $value): PaymentAdjustmentInterface
    {
        $this->paymentAdjustmentItems = $value;

        return $this;
    }

    public function setPaymentId(?int $value): PaymentAdjustmentInterface
    {
        $this->paymentId = $value;

        return $this;
    }

    public function setReasonCode(?string $value): PaymentAdjustmentInterface
    {
        $this->reasonCode = $value;

        return $this;
    }

    public function setShopTotalAdjustmentAmount(?int $value): PaymentAdjustmentInterface
    {
        $this->shopTotalAdjustmentAmount = $value;

        return $this;
    }

    public function setStatus(?string $value): PaymentAdjustmentInterface
    {
        $this->status = $value;

        return $this;
    }

    public function setTotalAdjustmentAmount(?int $value): PaymentAdjustmentInterface
    {
        $this->totalAdjustmentAmount = $value;

        return $this;
    }

    public function setTotalFeeAdjustmentAmount(?int $value): PaymentAdjustmentInterface
    {
        $this->totalFeeAdjustmentAmount = $value;

        return $this;
    }

    public function setUpdatedTimestamp(?int $value): PaymentAdjustmentInterface
    {
        $this->updatedTimestamp = $value;

        return $this;
    }

    public function setUpdateTimestamp(?int $value): PaymentAdjustmentInterface
    {
        $this->updateTimestamp = $value;

        return $this;
    }

    public function setUserId(?int $value): PaymentAdjustmentInterface
    {
        $this->userId = $value;

        return $this;
    }
}
