<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface TransactionInterface
{
    public function getBuyerCoupon(): ?float;

    public function getBuyerUserId(): ?int;

    public function getCreatedTimestamp(): ?int;

    public function getCreateTimestamp(): ?int;

    public function getDescription(): ?string;

    public function getExpectedShipDate(): ?int;

    public function getFileData(): ?string;

    public function getIsDigital(): ?bool;

    public function getListingId(): ?int;

    public function getListingImageId(): ?int;

    public function getMaxProcessingDays(): ?int;

    public function getMinProcessingDays(): ?int;

    public function getPaidTimestamp(): ?int;

    public function getPrice(): ?MoneyInterface;

    /**
     * @return array<int, ListingPropertyValueInterface>
     */
    public function getProductData(): array;

    public function getProductId(): ?int;

    public function getQuantity(): ?int;

    public function getReceiptId(): ?int;

    public function getSellerUserId(): ?int;

    public function getShippedTimestamp(): ?int;

    public function getShippingCost(): ?MoneyInterface;

    public function getShippingMethod(): ?string;

    public function getShippingProfileId(): ?int;

    public function getShippingUpgrade(): ?string;

    public function getShopCoupon(): ?float;

    public function getSku(): ?string;

    public function getTitle(): ?string;

    public function getTransactionId(): int;

    public function getTransactionType(): ?string;

    /**
     * @return array<int, TransactionVariationInterface>
     */
    public function getVariations(): array;

    public function setBuyerCoupon(?float $value): self;

    public function setBuyerUserId(?int $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setCreateTimestamp(?int $value): self;

    public function setDescription(?string $value): self;

    public function setExpectedShipDate(?int $value): self;

    public function setFileData(?string $value): self;

    public function setIsDigital(?bool $value): self;

    public function setListingId(?int $value): self;

    public function setListingImageId(?int $value): self;

    public function setMaxProcessingDays(?int $value): self;

    public function setMinProcessingDays(?int $value): self;

    public function setPaidTimestamp(?int $value): self;

    public function setPrice(?MoneyInterface $value): self;

    /**
     * @param array<int, ListingPropertyValueInterface> $value
     */
    public function setProductData(array $value): self;

    public function setProductId(?int $value): self;

    public function setQuantity(?int $value): self;

    public function setReceiptId(?int $value): self;

    public function setSellerUserId(?int $value): self;

    public function setShippedTimestamp(?int $value): self;

    public function setShippingCost(?MoneyInterface $value): self;

    public function setShippingMethod(?string $value): self;

    public function setShippingProfileId(?int $value): self;

    public function setShippingUpgrade(?string $value): self;

    public function setShopCoupon(?float $value): self;

    public function setSku(?string $value): self;

    public function setTitle(?string $value): self;

    public function setTransactionId(int $value): self;

    public function setTransactionType(?string $value): self;

    /**
     * @param array<int, TransactionVariationInterface> $value
     */
    public function setVariations(array $value): self;
}
