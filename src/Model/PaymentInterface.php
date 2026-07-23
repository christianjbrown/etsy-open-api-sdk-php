<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface PaymentInterface
{
    public function getAdjustedFees(): ?MoneyInterface;

    public function getAdjustedGross(): ?MoneyInterface;

    public function getAdjustedNet(): ?MoneyInterface;

    public function getAmountFees(): ?MoneyInterface;

    public function getAmountGross(): ?MoneyInterface;

    public function getAmountNet(): ?MoneyInterface;

    public function getBillingAddressId(): ?int;

    public function getBuyerCurrency(): ?string;

    public function getBuyerUserId(): ?int;

    public function getCreatedTimestamp(): ?int;

    public function getCreateTimestamp(): ?int;

    public function getCurrency(): ?string;

    /**
     * @return array<int, PaymentAdjustmentInterface>
     */
    public function getPaymentAdjustments(): array;

    public function getPaymentId(): int;

    public function getPostedFees(): ?MoneyInterface;

    public function getPostedGross(): ?MoneyInterface;

    public function getPostedNet(): ?MoneyInterface;

    public function getReceiptId(): ?int;

    public function getShippedTimestamp(): ?int;

    public function getShippingAddressId(): ?int;

    public function getShippingUserId(): ?int;

    public function getShopCurrency(): ?string;

    public function getShopId(): ?int;

    public function getStatus(): ?string;

    public function getUpdatedTimestamp(): ?int;

    public function getUpdateTimestamp(): ?int;

    public function setAdjustedFees(?MoneyInterface $value): self;

    public function setAdjustedGross(?MoneyInterface $value): self;

    public function setAdjustedNet(?MoneyInterface $value): self;

    public function setAmountFees(?MoneyInterface $value): self;

    public function setAmountGross(?MoneyInterface $value): self;

    public function setAmountNet(?MoneyInterface $value): self;

    public function setBillingAddressId(?int $value): self;

    public function setBuyerCurrency(?string $value): self;

    public function setBuyerUserId(?int $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setCreateTimestamp(?int $value): self;

    public function setCurrency(?string $value): self;

    /**
     * @param array<int, PaymentAdjustmentInterface> $value
     */
    public function setPaymentAdjustments(array $value): self;

    public function setPaymentId(int $value): self;

    public function setPostedFees(?MoneyInterface $value): self;

    public function setPostedGross(?MoneyInterface $value): self;

    public function setPostedNet(?MoneyInterface $value): self;

    public function setReceiptId(?int $value): self;

    public function setShippedTimestamp(?int $value): self;

    public function setShippingAddressId(?int $value): self;

    public function setShippingUserId(?int $value): self;

    public function setShopCurrency(?string $value): self;

    public function setShopId(?int $value): self;

    public function setStatus(?string $value): self;

    public function setUpdatedTimestamp(?int $value): self;

    public function setUpdateTimestamp(?int $value): self;
}
