<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Receipt implements ReceiptInterface
{
    private ?string $buyerEmail = null;
    private ?int $buyerUserId = null;
    private ?string $city = null;
    private ?string $countryIso = null;
    private ?int $createdTimestamp = null;
    private ?int $createTimestamp = null;
    private ?MoneyInterface $discountAmt = null;
    private ?string $firstLine = null;
    private ?string $formattedAddress = null;
    private ?string $giftMessage = null;
    private ?string $giftSender = null;
    private ?MoneyInterface $giftWrapPrice = null;
    private ?MoneyInterface $grandtotal = null;
    private ?bool $isGift = null;
    private ?bool $isPaid = null;
    private ?bool $isShipped = null;
    private ?string $messageFromBuyer = null;
    private ?string $messageFromPayment = null;
    private ?string $messageFromSeller = null;
    private ?string $name = null;
    private ?string $paymentEmail = null;
    private ?string $paymentMethod = null;
    private int $receiptId;
    private ?int $receiptType = null;

    /**
     * @var array<int, RefundInterface>
     */
    private array $refunds = [];
    private ?string $secondLine = null;
    private ?string $sellerEmail = null;
    private ?int $sellerUserId = null;

    /**
     * @var array<int, ShipmentInterface>
     */
    private array $shipments = [];
    private ?string $state = null;
    private ?string $status = null;
    private ?MoneyInterface $subtotal = null;
    private ?MoneyInterface $totalPrice = null;
    private ?MoneyInterface $totalShippingCost = null;
    private ?MoneyInterface $totalTaxCost = null;
    private ?MoneyInterface $totalVatCost = null;

    /**
     * @var array<int, TransactionInterface>
     */
    private array $transactions = [];
    private ?int $updatedTimestamp = null;
    private ?int $updateTimestamp = null;
    private ?string $zip = null;

    public function __construct(int $receiptId)
    {
        $this->receiptId = $receiptId;
    }

    public function getBuyerEmail(): ?string
    {
        return $this->buyerEmail;
    }

    public function getBuyerUserId(): ?int
    {
        return $this->buyerUserId;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCountryIso(): ?string
    {
        return $this->countryIso;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getCreateTimestamp(): ?int
    {
        return $this->createTimestamp;
    }

    public function getDiscountAmt(): ?MoneyInterface
    {
        return $this->discountAmt;
    }

    public function getFirstLine(): ?string
    {
        return $this->firstLine;
    }

    public function getFormattedAddress(): ?string
    {
        return $this->formattedAddress;
    }

    public function getGiftMessage(): ?string
    {
        return $this->giftMessage;
    }

    public function getGiftSender(): ?string
    {
        return $this->giftSender;
    }

    public function getGiftWrapPrice(): ?MoneyInterface
    {
        return $this->giftWrapPrice;
    }

    public function getGrandtotal(): ?MoneyInterface
    {
        return $this->grandtotal;
    }

    public function getIsGift(): ?bool
    {
        return $this->isGift;
    }

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function getIsShipped(): ?bool
    {
        return $this->isShipped;
    }

    public function getMessageFromBuyer(): ?string
    {
        return $this->messageFromBuyer;
    }

    public function getMessageFromPayment(): ?string
    {
        return $this->messageFromPayment;
    }

    public function getMessageFromSeller(): ?string
    {
        return $this->messageFromSeller;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPaymentEmail(): ?string
    {
        return $this->paymentEmail;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function getReceiptId(): int
    {
        return $this->receiptId;
    }

    public function getReceiptType(): ?int
    {
        return $this->receiptType;
    }

    /**
     * @return array<int, RefundInterface>
     */
    public function getRefunds(): array
    {
        return $this->refunds;
    }

    public function getSecondLine(): ?string
    {
        return $this->secondLine;
    }

    public function getSellerEmail(): ?string
    {
        return $this->sellerEmail;
    }

    public function getSellerUserId(): ?int
    {
        return $this->sellerUserId;
    }

    /**
     * @return array<int, ShipmentInterface>
     */
    public function getShipments(): array
    {
        return $this->shipments;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getSubtotal(): ?MoneyInterface
    {
        return $this->subtotal;
    }

    public function getTotalPrice(): ?MoneyInterface
    {
        return $this->totalPrice;
    }

    public function getTotalShippingCost(): ?MoneyInterface
    {
        return $this->totalShippingCost;
    }

    public function getTotalTaxCost(): ?MoneyInterface
    {
        return $this->totalTaxCost;
    }

    public function getTotalVatCost(): ?MoneyInterface
    {
        return $this->totalVatCost;
    }

    /**
     * @return array<int, TransactionInterface>
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function getUpdatedTimestamp(): ?int
    {
        return $this->updatedTimestamp;
    }

    public function getUpdateTimestamp(): ?int
    {
        return $this->updateTimestamp;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setBuyerEmail(?string $value): ReceiptInterface
    {
        $this->buyerEmail = $value;

        return $this;
    }

    public function setBuyerUserId(?int $value): ReceiptInterface
    {
        $this->buyerUserId = $value;

        return $this;
    }

    public function setCity(?string $value): ReceiptInterface
    {
        $this->city = $value;

        return $this;
    }

    public function setCountryIso(?string $value): ReceiptInterface
    {
        $this->countryIso = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): ReceiptInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCreateTimestamp(?int $value): ReceiptInterface
    {
        $this->createTimestamp = $value;

        return $this;
    }

    public function setDiscountAmt(?MoneyInterface $value): ReceiptInterface
    {
        $this->discountAmt = $value;

        return $this;
    }

    public function setFirstLine(?string $value): ReceiptInterface
    {
        $this->firstLine = $value;

        return $this;
    }

    public function setFormattedAddress(?string $value): ReceiptInterface
    {
        $this->formattedAddress = $value;

        return $this;
    }

    public function setGiftMessage(?string $value): ReceiptInterface
    {
        $this->giftMessage = $value;

        return $this;
    }

    public function setGiftSender(?string $value): ReceiptInterface
    {
        $this->giftSender = $value;

        return $this;
    }

    public function setGiftWrapPrice(?MoneyInterface $value): ReceiptInterface
    {
        $this->giftWrapPrice = $value;

        return $this;
    }

    public function setGrandtotal(?MoneyInterface $value): ReceiptInterface
    {
        $this->grandtotal = $value;

        return $this;
    }

    public function setIsGift(?bool $value): ReceiptInterface
    {
        $this->isGift = $value;

        return $this;
    }

    public function setIsPaid(?bool $value): ReceiptInterface
    {
        $this->isPaid = $value;

        return $this;
    }

    public function setIsShipped(?bool $value): ReceiptInterface
    {
        $this->isShipped = $value;

        return $this;
    }

    public function setMessageFromBuyer(?string $value): ReceiptInterface
    {
        $this->messageFromBuyer = $value;

        return $this;
    }

    public function setMessageFromPayment(?string $value): ReceiptInterface
    {
        $this->messageFromPayment = $value;

        return $this;
    }

    public function setMessageFromSeller(?string $value): ReceiptInterface
    {
        $this->messageFromSeller = $value;

        return $this;
    }

    public function setName(?string $value): ReceiptInterface
    {
        $this->name = $value;

        return $this;
    }

    public function setPaymentEmail(?string $value): ReceiptInterface
    {
        $this->paymentEmail = $value;

        return $this;
    }

    public function setPaymentMethod(?string $value): ReceiptInterface
    {
        $this->paymentMethod = $value;

        return $this;
    }

    public function setReceiptId(int $value): ReceiptInterface
    {
        $this->receiptId = $value;

        return $this;
    }

    public function setReceiptType(?int $value): ReceiptInterface
    {
        $this->receiptType = $value;

        return $this;
    }

    /**
     * @param array<int, RefundInterface> $value
     */
    public function setRefunds(array $value): ReceiptInterface
    {
        $this->refunds = $value;

        return $this;
    }

    public function setSecondLine(?string $value): ReceiptInterface
    {
        $this->secondLine = $value;

        return $this;
    }

    public function setSellerEmail(?string $value): ReceiptInterface
    {
        $this->sellerEmail = $value;

        return $this;
    }

    public function setSellerUserId(?int $value): ReceiptInterface
    {
        $this->sellerUserId = $value;

        return $this;
    }

    /**
     * @param array<int, ShipmentInterface> $value
     */
    public function setShipments(array $value): ReceiptInterface
    {
        $this->shipments = $value;

        return $this;
    }

    public function setState(?string $value): ReceiptInterface
    {
        $this->state = $value;

        return $this;
    }

    public function setStatus(?string $value): ReceiptInterface
    {
        $this->status = $value;

        return $this;
    }

    public function setSubtotal(?MoneyInterface $value): ReceiptInterface
    {
        $this->subtotal = $value;

        return $this;
    }

    public function setTotalPrice(?MoneyInterface $value): ReceiptInterface
    {
        $this->totalPrice = $value;

        return $this;
    }

    public function setTotalShippingCost(?MoneyInterface $value): ReceiptInterface
    {
        $this->totalShippingCost = $value;

        return $this;
    }

    public function setTotalTaxCost(?MoneyInterface $value): ReceiptInterface
    {
        $this->totalTaxCost = $value;

        return $this;
    }

    public function setTotalVatCost(?MoneyInterface $value): ReceiptInterface
    {
        $this->totalVatCost = $value;

        return $this;
    }

    /**
     * @param array<int, TransactionInterface> $value
     */
    public function setTransactions(array $value): ReceiptInterface
    {
        $this->transactions = $value;

        return $this;
    }

    public function setUpdatedTimestamp(?int $value): ReceiptInterface
    {
        $this->updatedTimestamp = $value;

        return $this;
    }

    public function setUpdateTimestamp(?int $value): ReceiptInterface
    {
        $this->updateTimestamp = $value;

        return $this;
    }

    public function setZip(?string $value): ReceiptInterface
    {
        $this->zip = $value;

        return $this;
    }
}
