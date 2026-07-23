<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface PaymentAdjustmentItemInterface
{
    public function getAdjustmentType(): ?string;

    public function getAmount(): ?int;

    public function getBillPaymentId(): ?int;

    public function getCreatedTimestamp(): ?int;

    public function getPaymentAdjustmentId(): ?int;

    public function getPaymentAdjustmentItemId(): ?int;

    public function getShopAmount(): ?int;

    public function getTransactionId(): ?int;

    public function getUpdatedTimestamp(): ?int;

    public function setAdjustmentType(?string $value): self;

    public function setAmount(?int $value): self;

    public function setBillPaymentId(?int $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setPaymentAdjustmentId(?int $value): self;

    public function setPaymentAdjustmentItemId(?int $value): self;

    public function setShopAmount(?int $value): self;

    public function setTransactionId(?int $value): self;

    public function setUpdatedTimestamp(?int $value): self;
}
