<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ReceiptInterface
{
    public function getBuyerEmail(): ?string;

    public function getBuyerUserId(): ?int;

    public function getCity(): ?string;

    public function getCountryIso(): ?string;

    public function getCreatedTimestamp(): ?int;

    public function getCreateTimestamp(): ?int;

    public function getDiscountAmt(): ?MoneyInterface;

    public function getFirstLine(): ?string;

    public function getFormattedAddress(): ?string;

    public function getGiftMessage(): ?string;

    public function getGiftSender(): ?string;

    public function getGiftWrapPrice(): ?MoneyInterface;

    public function getGrandtotal(): ?MoneyInterface;

    public function getIsGift(): ?bool;

    public function getIsPaid(): ?bool;

    public function getIsShipped(): ?bool;

    public function getMessageFromBuyer(): ?string;

    public function getMessageFromPayment(): ?string;

    public function getMessageFromSeller(): ?string;

    public function getName(): ?string;

    public function getPaymentEmail(): ?string;

    public function getPaymentMethod(): ?string;

    public function getReceiptId(): int;

    public function getReceiptType(): ?int;

    /**
     * @return array<int, RefundInterface>
     */
    public function getRefunds(): array;

    public function getSecondLine(): ?string;

    public function getSellerEmail(): ?string;

    public function getSellerUserId(): ?int;

    /**
     * @return array<int, ShipmentInterface>
     */
    public function getShipments(): array;

    public function getState(): ?string;

    public function getStatus(): ?string;

    public function getSubtotal(): ?MoneyInterface;

    public function getTotalPrice(): ?MoneyInterface;

    public function getTotalShippingCost(): ?MoneyInterface;

    public function getTotalTaxCost(): ?MoneyInterface;

    public function getTotalVatCost(): ?MoneyInterface;

    /**
     * @return array<int, TransactionInterface>
     */
    public function getTransactions(): array;

    public function getUpdatedTimestamp(): ?int;

    public function getUpdateTimestamp(): ?int;

    public function getZip(): ?string;

    public function setBuyerEmail(?string $value): self;

    public function setBuyerUserId(?int $value): self;

    public function setCity(?string $value): self;

    public function setCountryIso(?string $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setCreateTimestamp(?int $value): self;

    public function setDiscountAmt(?MoneyInterface $value): self;

    public function setFirstLine(?string $value): self;

    public function setFormattedAddress(?string $value): self;

    public function setGiftMessage(?string $value): self;

    public function setGiftSender(?string $value): self;

    public function setGiftWrapPrice(?MoneyInterface $value): self;

    public function setGrandtotal(?MoneyInterface $value): self;

    public function setIsGift(?bool $value): self;

    public function setIsPaid(?bool $value): self;

    public function setIsShipped(?bool $value): self;

    public function setMessageFromBuyer(?string $value): self;

    public function setMessageFromPayment(?string $value): self;

    public function setMessageFromSeller(?string $value): self;

    public function setName(?string $value): self;

    public function setPaymentEmail(?string $value): self;

    public function setPaymentMethod(?string $value): self;

    public function setReceiptId(int $value): self;

    public function setReceiptType(?int $value): self;

    /**
     * @param array<int, RefundInterface> $value
     */
    public function setRefunds(array $value): self;

    public function setSecondLine(?string $value): self;

    public function setSellerEmail(?string $value): self;

    public function setSellerUserId(?int $value): self;

    /**
     * @param array<int, ShipmentInterface> $value
     */
    public function setShipments(array $value): self;

    public function setState(?string $value): self;

    public function setStatus(?string $value): self;

    public function setSubtotal(?MoneyInterface $value): self;

    public function setTotalPrice(?MoneyInterface $value): self;

    public function setTotalShippingCost(?MoneyInterface $value): self;

    public function setTotalTaxCost(?MoneyInterface $value): self;

    public function setTotalVatCost(?MoneyInterface $value): self;

    /**
     * @param array<int, TransactionInterface> $value
     */
    public function setTransactions(array $value): self;

    public function setUpdatedTimestamp(?int $value): self;

    public function setUpdateTimestamp(?int $value): self;

    public function setZip(?string $value): self;
}
