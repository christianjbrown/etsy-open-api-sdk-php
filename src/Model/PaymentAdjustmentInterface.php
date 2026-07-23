<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface PaymentAdjustmentInterface
{
    public function getBuyerTotalAdjustmentAmount(): ?int;

    public function getCreatedTimestamp(): ?int;

    public function getCreateTimestamp(): ?int;

    public function getIsSuccess(): ?bool;

    public function getPaymentAdjustmentId(): int;

    /**
     * @return array<int, PaymentAdjustmentItemInterface>
     */
    public function getPaymentAdjustmentItems(): array;

    public function getPaymentId(): ?int;

    public function getReasonCode(): ?string;

    public function getShopTotalAdjustmentAmount(): ?int;

    public function getStatus(): ?string;

    public function getTotalAdjustmentAmount(): ?int;

    public function getTotalFeeAdjustmentAmount(): ?int;

    public function getUpdatedTimestamp(): ?int;

    public function getUpdateTimestamp(): ?int;

    public function getUserId(): ?int;

    public function setBuyerTotalAdjustmentAmount(?int $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setCreateTimestamp(?int $value): self;

    public function setIsSuccess(?bool $value): self;

    public function setPaymentAdjustmentId(int $value): self;

    /**
     * @param array<int, PaymentAdjustmentItemInterface> $value
     */
    public function setPaymentAdjustmentItems(array $value): self;

    public function setPaymentId(?int $value): self;

    public function setReasonCode(?string $value): self;

    public function setShopTotalAdjustmentAmount(?int $value): self;

    public function setStatus(?string $value): self;

    public function setTotalAdjustmentAmount(?int $value): self;

    public function setTotalFeeAdjustmentAmount(?int $value): self;

    public function setUpdatedTimestamp(?int $value): self;

    public function setUpdateTimestamp(?int $value): self;

    public function setUserId(?int $value): self;
}
