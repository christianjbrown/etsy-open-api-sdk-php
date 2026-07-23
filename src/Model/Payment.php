<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Payment implements PaymentInterface
{
    private ?MoneyInterface $adjustedFees = null;
    private ?MoneyInterface $adjustedGross = null;
    private ?MoneyInterface $adjustedNet = null;
    private ?MoneyInterface $amountFees = null;
    private ?MoneyInterface $amountGross = null;
    private ?MoneyInterface $amountNet = null;
    private ?int $billingAddressId = null;
    private ?string $buyerCurrency = null;
    private ?int $buyerUserId = null;
    private ?int $createdTimestamp = null;
    private ?int $createTimestamp = null;
    private ?string $currency = null;

    /**
     * @var array<int, PaymentAdjustmentInterface>
     */
    private array $paymentAdjustments = [];
    private int $paymentId;
    private ?MoneyInterface $postedFees = null;
    private ?MoneyInterface $postedGross = null;
    private ?MoneyInterface $postedNet = null;
    private ?int $receiptId = null;
    private ?int $shippedTimestamp = null;
    private ?int $shippingAddressId = null;
    private ?int $shippingUserId = null;
    private ?string $shopCurrency = null;
    private ?int $shopId = null;
    private ?string $status = null;
    private ?int $updatedTimestamp = null;
    private ?int $updateTimestamp = null;

    public function __construct(int $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function getAdjustedFees(): ?MoneyInterface
    {
        return $this->adjustedFees;
    }

    public function getAdjustedGross(): ?MoneyInterface
    {
        return $this->adjustedGross;
    }

    public function getAdjustedNet(): ?MoneyInterface
    {
        return $this->adjustedNet;
    }

    public function getAmountFees(): ?MoneyInterface
    {
        return $this->amountFees;
    }

    public function getAmountGross(): ?MoneyInterface
    {
        return $this->amountGross;
    }

    public function getAmountNet(): ?MoneyInterface
    {
        return $this->amountNet;
    }

    public function getBillingAddressId(): ?int
    {
        return $this->billingAddressId;
    }

    public function getBuyerCurrency(): ?string
    {
        return $this->buyerCurrency;
    }

    public function getBuyerUserId(): ?int
    {
        return $this->buyerUserId;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getCreateTimestamp(): ?int
    {
        return $this->createTimestamp;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @return array<int, PaymentAdjustmentInterface>
     */
    public function getPaymentAdjustments(): array
    {
        return $this->paymentAdjustments;
    }

    public function getPaymentId(): int
    {
        return $this->paymentId;
    }

    public function getPostedFees(): ?MoneyInterface
    {
        return $this->postedFees;
    }

    public function getPostedGross(): ?MoneyInterface
    {
        return $this->postedGross;
    }

    public function getPostedNet(): ?MoneyInterface
    {
        return $this->postedNet;
    }

    public function getReceiptId(): ?int
    {
        return $this->receiptId;
    }

    public function getShippedTimestamp(): ?int
    {
        return $this->shippedTimestamp;
    }

    public function getShippingAddressId(): ?int
    {
        return $this->shippingAddressId;
    }

    public function getShippingUserId(): ?int
    {
        return $this->shippingUserId;
    }

    public function getShopCurrency(): ?string
    {
        return $this->shopCurrency;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getUpdatedTimestamp(): ?int
    {
        return $this->updatedTimestamp;
    }

    public function getUpdateTimestamp(): ?int
    {
        return $this->updateTimestamp;
    }

    public function setAdjustedFees(?MoneyInterface $value): PaymentInterface
    {
        $this->adjustedFees = $value;

        return $this;
    }

    public function setAdjustedGross(?MoneyInterface $value): PaymentInterface
    {
        $this->adjustedGross = $value;

        return $this;
    }

    public function setAdjustedNet(?MoneyInterface $value): PaymentInterface
    {
        $this->adjustedNet = $value;

        return $this;
    }

    public function setAmountFees(?MoneyInterface $value): PaymentInterface
    {
        $this->amountFees = $value;

        return $this;
    }

    public function setAmountGross(?MoneyInterface $value): PaymentInterface
    {
        $this->amountGross = $value;

        return $this;
    }

    public function setAmountNet(?MoneyInterface $value): PaymentInterface
    {
        $this->amountNet = $value;

        return $this;
    }

    public function setBillingAddressId(?int $value): PaymentInterface
    {
        $this->billingAddressId = $value;

        return $this;
    }

    public function setBuyerCurrency(?string $value): PaymentInterface
    {
        $this->buyerCurrency = $value;

        return $this;
    }

    public function setBuyerUserId(?int $value): PaymentInterface
    {
        $this->buyerUserId = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): PaymentInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCreateTimestamp(?int $value): PaymentInterface
    {
        $this->createTimestamp = $value;

        return $this;
    }

    public function setCurrency(?string $value): PaymentInterface
    {
        $this->currency = $value;

        return $this;
    }

    /**
     * @param array<int, PaymentAdjustmentInterface> $value
     */
    public function setPaymentAdjustments(array $value): PaymentInterface
    {
        $this->paymentAdjustments = $value;

        return $this;
    }

    public function setPaymentId(int $value): PaymentInterface
    {
        $this->paymentId = $value;

        return $this;
    }

    public function setPostedFees(?MoneyInterface $value): PaymentInterface
    {
        $this->postedFees = $value;

        return $this;
    }

    public function setPostedGross(?MoneyInterface $value): PaymentInterface
    {
        $this->postedGross = $value;

        return $this;
    }

    public function setPostedNet(?MoneyInterface $value): PaymentInterface
    {
        $this->postedNet = $value;

        return $this;
    }

    public function setReceiptId(?int $value): PaymentInterface
    {
        $this->receiptId = $value;

        return $this;
    }

    public function setShippedTimestamp(?int $value): PaymentInterface
    {
        $this->shippedTimestamp = $value;

        return $this;
    }

    public function setShippingAddressId(?int $value): PaymentInterface
    {
        $this->shippingAddressId = $value;

        return $this;
    }

    public function setShippingUserId(?int $value): PaymentInterface
    {
        $this->shippingUserId = $value;

        return $this;
    }

    public function setShopCurrency(?string $value): PaymentInterface
    {
        $this->shopCurrency = $value;

        return $this;
    }

    public function setShopId(?int $value): PaymentInterface
    {
        $this->shopId = $value;

        return $this;
    }

    public function setStatus(?string $value): PaymentInterface
    {
        $this->status = $value;

        return $this;
    }

    public function setUpdatedTimestamp(?int $value): PaymentInterface
    {
        $this->updatedTimestamp = $value;

        return $this;
    }

    public function setUpdateTimestamp(?int $value): PaymentInterface
    {
        $this->updateTimestamp = $value;

        return $this;
    }
}
