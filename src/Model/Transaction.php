<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Transaction implements TransactionInterface
{
    private ?float $buyerCoupon = null;
    private ?int $buyerUserId = null;
    private ?int $createdTimestamp = null;
    private ?int $createTimestamp = null;
    private ?string $description = null;
    private ?int $expectedShipDate = null;
    private ?string $fileData = null;
    private ?bool $isDigital = null;
    private ?int $listingId = null;
    private ?int $listingImageId = null;
    private ?int $maxProcessingDays = null;
    private ?int $minProcessingDays = null;
    private ?int $paidTimestamp = null;
    private ?MoneyInterface $price = null;

    /**
     * @var array<int, ListingPropertyValueInterface>
     */
    private array $productData = [];
    private ?int $productId = null;
    private ?int $quantity = null;
    private ?int $receiptId = null;
    private ?int $sellerUserId = null;
    private ?int $shippedTimestamp = null;
    private ?MoneyInterface $shippingCost = null;
    private ?string $shippingMethod = null;
    private ?int $shippingProfileId = null;
    private ?string $shippingUpgrade = null;
    private ?float $shopCoupon = null;
    private ?string $sku = null;
    private ?string $title = null;
    private int $transactionId;
    private ?string $transactionType = null;

    /**
     * @var array<int, TransactionVariationInterface>
     */
    private array $variations = [];

    public function __construct(int $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function getBuyerCoupon(): ?float
    {
        return $this->buyerCoupon;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getExpectedShipDate(): ?int
    {
        return $this->expectedShipDate;
    }

    public function getFileData(): ?string
    {
        return $this->fileData;
    }

    public function getIsDigital(): ?bool
    {
        return $this->isDigital;
    }

    public function getListingId(): ?int
    {
        return $this->listingId;
    }

    public function getListingImageId(): ?int
    {
        return $this->listingImageId;
    }

    public function getMaxProcessingDays(): ?int
    {
        return $this->maxProcessingDays;
    }

    public function getMinProcessingDays(): ?int
    {
        return $this->minProcessingDays;
    }

    public function getPaidTimestamp(): ?int
    {
        return $this->paidTimestamp;
    }

    public function getPrice(): ?MoneyInterface
    {
        return $this->price;
    }

    /**
     * @return array<int, ListingPropertyValueInterface>
     */
    public function getProductData(): array
    {
        return $this->productData;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getReceiptId(): ?int
    {
        return $this->receiptId;
    }

    public function getSellerUserId(): ?int
    {
        return $this->sellerUserId;
    }

    public function getShippedTimestamp(): ?int
    {
        return $this->shippedTimestamp;
    }

    public function getShippingCost(): ?MoneyInterface
    {
        return $this->shippingCost;
    }

    public function getShippingMethod(): ?string
    {
        return $this->shippingMethod;
    }

    public function getShippingProfileId(): ?int
    {
        return $this->shippingProfileId;
    }

    public function getShippingUpgrade(): ?string
    {
        return $this->shippingUpgrade;
    }

    public function getShopCoupon(): ?float
    {
        return $this->shopCoupon;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    public function getTransactionType(): ?string
    {
        return $this->transactionType;
    }

    /**
     * @return array<int, TransactionVariationInterface>
     */
    public function getVariations(): array
    {
        return $this->variations;
    }

    public function setBuyerCoupon(?float $value): TransactionInterface
    {
        $this->buyerCoupon = $value;

        return $this;
    }

    public function setBuyerUserId(?int $value): TransactionInterface
    {
        $this->buyerUserId = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): TransactionInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCreateTimestamp(?int $value): TransactionInterface
    {
        $this->createTimestamp = $value;

        return $this;
    }

    public function setDescription(?string $value): TransactionInterface
    {
        $this->description = $value;

        return $this;
    }

    public function setExpectedShipDate(?int $value): TransactionInterface
    {
        $this->expectedShipDate = $value;

        return $this;
    }

    public function setFileData(?string $value): TransactionInterface
    {
        $this->fileData = $value;

        return $this;
    }

    public function setIsDigital(?bool $value): TransactionInterface
    {
        $this->isDigital = $value;

        return $this;
    }

    public function setListingId(?int $value): TransactionInterface
    {
        $this->listingId = $value;

        return $this;
    }

    public function setListingImageId(?int $value): TransactionInterface
    {
        $this->listingImageId = $value;

        return $this;
    }

    public function setMaxProcessingDays(?int $value): TransactionInterface
    {
        $this->maxProcessingDays = $value;

        return $this;
    }

    public function setMinProcessingDays(?int $value): TransactionInterface
    {
        $this->minProcessingDays = $value;

        return $this;
    }

    public function setPaidTimestamp(?int $value): TransactionInterface
    {
        $this->paidTimestamp = $value;

        return $this;
    }

    public function setPrice(?MoneyInterface $value): TransactionInterface
    {
        $this->price = $value;

        return $this;
    }

    /**
     * @param array<int, ListingPropertyValueInterface> $value
     */
    public function setProductData(array $value): TransactionInterface
    {
        $this->productData = $value;

        return $this;
    }

    public function setProductId(?int $value): TransactionInterface
    {
        $this->productId = $value;

        return $this;
    }

    public function setQuantity(?int $value): TransactionInterface
    {
        $this->quantity = $value;

        return $this;
    }

    public function setReceiptId(?int $value): TransactionInterface
    {
        $this->receiptId = $value;

        return $this;
    }

    public function setSellerUserId(?int $value): TransactionInterface
    {
        $this->sellerUserId = $value;

        return $this;
    }

    public function setShippedTimestamp(?int $value): TransactionInterface
    {
        $this->shippedTimestamp = $value;

        return $this;
    }

    public function setShippingCost(?MoneyInterface $value): TransactionInterface
    {
        $this->shippingCost = $value;

        return $this;
    }

    public function setShippingMethod(?string $value): TransactionInterface
    {
        $this->shippingMethod = $value;

        return $this;
    }

    public function setShippingProfileId(?int $value): TransactionInterface
    {
        $this->shippingProfileId = $value;

        return $this;
    }

    public function setShippingUpgrade(?string $value): TransactionInterface
    {
        $this->shippingUpgrade = $value;

        return $this;
    }

    public function setShopCoupon(?float $value): TransactionInterface
    {
        $this->shopCoupon = $value;

        return $this;
    }

    public function setSku(?string $value): TransactionInterface
    {
        $this->sku = $value;

        return $this;
    }

    public function setTitle(?string $value): TransactionInterface
    {
        $this->title = $value;

        return $this;
    }

    public function setTransactionId(int $value): TransactionInterface
    {
        $this->transactionId = $value;

        return $this;
    }

    public function setTransactionType(?string $value): TransactionInterface
    {
        $this->transactionType = $value;

        return $this;
    }

    /**
     * @param array<int, TransactionVariationInterface> $value
     */
    public function setVariations(array $value): TransactionInterface
    {
        $this->variations = $value;

        return $this;
    }
}
