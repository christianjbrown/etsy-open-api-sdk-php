<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShopInterface
{
    public function getAcceptsCustomRequests(): ?bool;

    public function getAnnouncement(): ?string;

    public function getCreateDate(): ?int;

    public function getCreatedTimestamp(): ?int;

    public function getCurrencyCode(): ?string;

    public function getDigitalListingCount(): ?int;

    public function getDigitalSaleMessage(): ?string;

    public function getHasOnboardedStructuredPolicies(): ?bool;

    public function getHasUnstructuredPolicies(): ?bool;

    public function getIconUrlFullxfull(): ?string;

    public function getImageUrl760x100(): ?string;

    public function getIncludeDisputeFormLink(): ?bool;

    public function getIsCalculatedEligible(): ?bool;

    public function getIsDirectCheckoutOnboarded(): ?bool;

    public function getIsEtsyPaymentsOnboarded(): ?bool;

    public function getIsOptedInToBuyerPromise(): ?bool;

    public function getIsShopUsBased(): ?bool;

    public function getIsUsingStructuredPolicies(): ?bool;

    public function getIsVacation(): ?bool;

    /**
     * @return array<int, string>
     */
    public function getLanguages(): array;

    public function getListingActiveCount(): ?int;

    public function getLoginName(): ?string;

    public function getNumFavorers(): ?int;

    public function getPolicyAdditional(): ?string;

    public function getPolicyHasPrivateReceiptInfo(): ?bool;

    public function getPolicyPayment(): ?string;

    public function getPolicyPrivacy(): ?string;

    public function getPolicyRefunds(): ?string;

    public function getPolicySellerInfo(): ?string;

    public function getPolicyShipping(): ?string;

    public function getPolicyUpdateDate(): ?int;

    public function getPolicyWelcome(): ?string;

    public function getReviewAverage(): ?float;

    public function getReviewCount(): ?int;

    public function getSaleMessage(): ?string;

    public function getShippingFromCountryIso(): ?string;

    public function getShopId(): int;

    public function getShopLocationCountryIso(): ?string;

    public function getShopName(): ?string;

    public function getTitle(): ?string;

    public function getTransactionSoldCount(): ?int;

    public function getUpdateDate(): ?int;

    public function getUpdatedTimestamp(): ?int;

    public function getUrl(): ?string;

    public function getUserId(): ?int;

    public function getVacationAutoreply(): ?string;

    public function getVacationMessage(): ?string;

    public function setAcceptsCustomRequests(?bool $value): self;

    public function setAnnouncement(?string $value): self;

    public function setCreateDate(?int $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setCurrencyCode(?string $value): self;

    public function setDigitalListingCount(?int $value): self;

    public function setDigitalSaleMessage(?string $value): self;

    public function setHasOnboardedStructuredPolicies(?bool $value): self;

    public function setHasUnstructuredPolicies(?bool $value): self;

    public function setIconUrlFullxfull(?string $value): self;

    public function setImageUrl760x100(?string $value): self;

    public function setIncludeDisputeFormLink(?bool $value): self;

    public function setIsCalculatedEligible(?bool $value): self;

    public function setIsDirectCheckoutOnboarded(?bool $value): self;

    public function setIsEtsyPaymentsOnboarded(?bool $value): self;

    public function setIsOptedInToBuyerPromise(?bool $value): self;

    public function setIsShopUsBased(?bool $value): self;

    public function setIsUsingStructuredPolicies(?bool $value): self;

    public function setIsVacation(?bool $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setLanguages(array $value): self;

    public function setListingActiveCount(?int $value): self;

    public function setLoginName(?string $value): self;

    public function setNumFavorers(?int $value): self;

    public function setPolicyAdditional(?string $value): self;

    public function setPolicyHasPrivateReceiptInfo(?bool $value): self;

    public function setPolicyPayment(?string $value): self;

    public function setPolicyPrivacy(?string $value): self;

    public function setPolicyRefunds(?string $value): self;

    public function setPolicySellerInfo(?string $value): self;

    public function setPolicyShipping(?string $value): self;

    public function setPolicyUpdateDate(?int $value): self;

    public function setPolicyWelcome(?string $value): self;

    public function setReviewAverage(?float $value): self;

    public function setReviewCount(?int $value): self;

    public function setSaleMessage(?string $value): self;

    public function setShippingFromCountryIso(?string $value): self;

    public function setShopId(int $value): self;

    public function setShopLocationCountryIso(?string $value): self;

    public function setShopName(?string $value): self;

    public function setTitle(?string $value): self;

    public function setTransactionSoldCount(?int $value): self;

    public function setUpdateDate(?int $value): self;

    public function setUpdatedTimestamp(?int $value): self;

    public function setUrl(?string $value): self;

    public function setUserId(?int $value): self;

    public function setVacationAutoreply(?string $value): self;

    public function setVacationMessage(?string $value): self;
}
