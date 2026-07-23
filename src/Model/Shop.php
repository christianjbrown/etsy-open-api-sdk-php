<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Shop implements ShopInterface
{
    private ?bool $acceptsCustomRequests = null;
    private ?string $announcement = null;
    private ?int $createDate = null;
    private ?int $createdTimestamp = null;
    private ?string $currencyCode = null;
    private ?int $digitalListingCount = null;
    private ?string $digitalSaleMessage = null;
    private ?bool $hasOnboardedStructuredPolicies = null;
    private ?bool $hasUnstructuredPolicies = null;
    private ?string $iconUrlFullxfull = null;
    private ?string $imageUrl760x100 = null;
    private ?bool $includeDisputeFormLink = null;
    private ?bool $isCalculatedEligible = null;
    private ?bool $isDirectCheckoutOnboarded = null;
    private ?bool $isEtsyPaymentsOnboarded = null;
    private ?bool $isOptedInToBuyerPromise = null;
    private ?bool $isShopUsBased = null;
    private ?bool $isUsingStructuredPolicies = null;
    private ?bool $isVacation = null;

    /**
     * @var array<int, string>
     */
    private array $languages = [];
    private ?int $listingActiveCount = null;
    private ?string $loginName = null;
    private ?int $numFavorers = null;
    private ?string $policyAdditional = null;
    private ?bool $policyHasPrivateReceiptInfo = null;
    private ?string $policyPayment = null;
    private ?string $policyPrivacy = null;
    private ?string $policyRefunds = null;
    private ?string $policySellerInfo = null;
    private ?string $policyShipping = null;
    private ?int $policyUpdateDate = null;
    private ?string $policyWelcome = null;
    private ?float $reviewAverage = null;
    private ?int $reviewCount = null;
    private ?string $saleMessage = null;
    private ?string $shippingFromCountryIso = null;
    private int $shopId;
    private ?string $shopLocationCountryIso = null;
    private ?string $shopName = null;
    private ?string $title = null;
    private ?int $transactionSoldCount = null;
    private ?int $updateDate = null;
    private ?int $updatedTimestamp = null;
    private ?string $url = null;
    private ?int $userId = null;
    private ?string $vacationAutoreply = null;
    private ?string $vacationMessage = null;

    public function __construct(int $shopId)
    {
        $this->shopId = $shopId;
    }

    public function getAcceptsCustomRequests(): ?bool
    {
        return $this->acceptsCustomRequests;
    }

    public function getAnnouncement(): ?string
    {
        return $this->announcement;
    }

    public function getCreateDate(): ?int
    {
        return $this->createDate;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function getDigitalListingCount(): ?int
    {
        return $this->digitalListingCount;
    }

    public function getDigitalSaleMessage(): ?string
    {
        return $this->digitalSaleMessage;
    }

    public function getHasOnboardedStructuredPolicies(): ?bool
    {
        return $this->hasOnboardedStructuredPolicies;
    }

    public function getHasUnstructuredPolicies(): ?bool
    {
        return $this->hasUnstructuredPolicies;
    }

    public function getIconUrlFullxfull(): ?string
    {
        return $this->iconUrlFullxfull;
    }

    public function getImageUrl760x100(): ?string
    {
        return $this->imageUrl760x100;
    }

    public function getIncludeDisputeFormLink(): ?bool
    {
        return $this->includeDisputeFormLink;
    }

    public function getIsCalculatedEligible(): ?bool
    {
        return $this->isCalculatedEligible;
    }

    public function getIsDirectCheckoutOnboarded(): ?bool
    {
        return $this->isDirectCheckoutOnboarded;
    }

    public function getIsEtsyPaymentsOnboarded(): ?bool
    {
        return $this->isEtsyPaymentsOnboarded;
    }

    public function getIsOptedInToBuyerPromise(): ?bool
    {
        return $this->isOptedInToBuyerPromise;
    }

    public function getIsShopUsBased(): ?bool
    {
        return $this->isShopUsBased;
    }

    public function getIsUsingStructuredPolicies(): ?bool
    {
        return $this->isUsingStructuredPolicies;
    }

    public function getIsVacation(): ?bool
    {
        return $this->isVacation;
    }

    /**
     * @return array<int, string>
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function getListingActiveCount(): ?int
    {
        return $this->listingActiveCount;
    }

    public function getLoginName(): ?string
    {
        return $this->loginName;
    }

    public function getNumFavorers(): ?int
    {
        return $this->numFavorers;
    }

    public function getPolicyAdditional(): ?string
    {
        return $this->policyAdditional;
    }

    public function getPolicyHasPrivateReceiptInfo(): ?bool
    {
        return $this->policyHasPrivateReceiptInfo;
    }

    public function getPolicyPayment(): ?string
    {
        return $this->policyPayment;
    }

    public function getPolicyPrivacy(): ?string
    {
        return $this->policyPrivacy;
    }

    public function getPolicyRefunds(): ?string
    {
        return $this->policyRefunds;
    }

    public function getPolicySellerInfo(): ?string
    {
        return $this->policySellerInfo;
    }

    public function getPolicyShipping(): ?string
    {
        return $this->policyShipping;
    }

    public function getPolicyUpdateDate(): ?int
    {
        return $this->policyUpdateDate;
    }

    public function getPolicyWelcome(): ?string
    {
        return $this->policyWelcome;
    }

    public function getReviewAverage(): ?float
    {
        return $this->reviewAverage;
    }

    public function getReviewCount(): ?int
    {
        return $this->reviewCount;
    }

    public function getSaleMessage(): ?string
    {
        return $this->saleMessage;
    }

    public function getShippingFromCountryIso(): ?string
    {
        return $this->shippingFromCountryIso;
    }

    public function getShopId(): int
    {
        return $this->shopId;
    }

    public function getShopLocationCountryIso(): ?string
    {
        return $this->shopLocationCountryIso;
    }

    public function getShopName(): ?string
    {
        return $this->shopName;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getTransactionSoldCount(): ?int
    {
        return $this->transactionSoldCount;
    }

    public function getUpdateDate(): ?int
    {
        return $this->updateDate;
    }

    public function getUpdatedTimestamp(): ?int
    {
        return $this->updatedTimestamp;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getVacationAutoreply(): ?string
    {
        return $this->vacationAutoreply;
    }

    public function getVacationMessage(): ?string
    {
        return $this->vacationMessage;
    }

    public function setAcceptsCustomRequests(?bool $value): ShopInterface
    {
        $this->acceptsCustomRequests = $value;

        return $this;
    }

    public function setAnnouncement(?string $value): ShopInterface
    {
        $this->announcement = $value;

        return $this;
    }

    public function setCreateDate(?int $value): ShopInterface
    {
        $this->createDate = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): ShopInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCurrencyCode(?string $value): ShopInterface
    {
        $this->currencyCode = $value;

        return $this;
    }

    public function setDigitalListingCount(?int $value): ShopInterface
    {
        $this->digitalListingCount = $value;

        return $this;
    }

    public function setDigitalSaleMessage(?string $value): ShopInterface
    {
        $this->digitalSaleMessage = $value;

        return $this;
    }

    public function setHasOnboardedStructuredPolicies(?bool $value): ShopInterface
    {
        $this->hasOnboardedStructuredPolicies = $value;

        return $this;
    }

    public function setHasUnstructuredPolicies(?bool $value): ShopInterface
    {
        $this->hasUnstructuredPolicies = $value;

        return $this;
    }

    public function setIconUrlFullxfull(?string $value): ShopInterface
    {
        $this->iconUrlFullxfull = $value;

        return $this;
    }

    public function setImageUrl760x100(?string $value): ShopInterface
    {
        $this->imageUrl760x100 = $value;

        return $this;
    }

    public function setIncludeDisputeFormLink(?bool $value): ShopInterface
    {
        $this->includeDisputeFormLink = $value;

        return $this;
    }

    public function setIsCalculatedEligible(?bool $value): ShopInterface
    {
        $this->isCalculatedEligible = $value;

        return $this;
    }

    public function setIsDirectCheckoutOnboarded(?bool $value): ShopInterface
    {
        $this->isDirectCheckoutOnboarded = $value;

        return $this;
    }

    public function setIsEtsyPaymentsOnboarded(?bool $value): ShopInterface
    {
        $this->isEtsyPaymentsOnboarded = $value;

        return $this;
    }

    public function setIsOptedInToBuyerPromise(?bool $value): ShopInterface
    {
        $this->isOptedInToBuyerPromise = $value;

        return $this;
    }

    public function setIsShopUsBased(?bool $value): ShopInterface
    {
        $this->isShopUsBased = $value;

        return $this;
    }

    public function setIsUsingStructuredPolicies(?bool $value): ShopInterface
    {
        $this->isUsingStructuredPolicies = $value;

        return $this;
    }

    public function setIsVacation(?bool $value): ShopInterface
    {
        $this->isVacation = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setLanguages(array $value): ShopInterface
    {
        $this->languages = $value;

        return $this;
    }

    public function setListingActiveCount(?int $value): ShopInterface
    {
        $this->listingActiveCount = $value;

        return $this;
    }

    public function setLoginName(?string $value): ShopInterface
    {
        $this->loginName = $value;

        return $this;
    }

    public function setNumFavorers(?int $value): ShopInterface
    {
        $this->numFavorers = $value;

        return $this;
    }

    public function setPolicyAdditional(?string $value): ShopInterface
    {
        $this->policyAdditional = $value;

        return $this;
    }

    public function setPolicyHasPrivateReceiptInfo(?bool $value): ShopInterface
    {
        $this->policyHasPrivateReceiptInfo = $value;

        return $this;
    }

    public function setPolicyPayment(?string $value): ShopInterface
    {
        $this->policyPayment = $value;

        return $this;
    }

    public function setPolicyPrivacy(?string $value): ShopInterface
    {
        $this->policyPrivacy = $value;

        return $this;
    }

    public function setPolicyRefunds(?string $value): ShopInterface
    {
        $this->policyRefunds = $value;

        return $this;
    }

    public function setPolicySellerInfo(?string $value): ShopInterface
    {
        $this->policySellerInfo = $value;

        return $this;
    }

    public function setPolicyShipping(?string $value): ShopInterface
    {
        $this->policyShipping = $value;

        return $this;
    }

    public function setPolicyUpdateDate(?int $value): ShopInterface
    {
        $this->policyUpdateDate = $value;

        return $this;
    }

    public function setPolicyWelcome(?string $value): ShopInterface
    {
        $this->policyWelcome = $value;

        return $this;
    }

    public function setReviewAverage(?float $value): ShopInterface
    {
        $this->reviewAverage = $value;

        return $this;
    }

    public function setReviewCount(?int $value): ShopInterface
    {
        $this->reviewCount = $value;

        return $this;
    }

    public function setSaleMessage(?string $value): ShopInterface
    {
        $this->saleMessage = $value;

        return $this;
    }

    public function setShippingFromCountryIso(?string $value): ShopInterface
    {
        $this->shippingFromCountryIso = $value;

        return $this;
    }

    public function setShopId(int $value): ShopInterface
    {
        $this->shopId = $value;

        return $this;
    }

    public function setShopLocationCountryIso(?string $value): ShopInterface
    {
        $this->shopLocationCountryIso = $value;

        return $this;
    }

    public function setShopName(?string $value): ShopInterface
    {
        $this->shopName = $value;

        return $this;
    }

    public function setTitle(?string $value): ShopInterface
    {
        $this->title = $value;

        return $this;
    }

    public function setTransactionSoldCount(?int $value): ShopInterface
    {
        $this->transactionSoldCount = $value;

        return $this;
    }

    public function setUpdateDate(?int $value): ShopInterface
    {
        $this->updateDate = $value;

        return $this;
    }

    public function setUpdatedTimestamp(?int $value): ShopInterface
    {
        $this->updatedTimestamp = $value;

        return $this;
    }

    public function setUrl(?string $value): ShopInterface
    {
        $this->url = $value;

        return $this;
    }

    public function setUserId(?int $value): ShopInterface
    {
        $this->userId = $value;

        return $this;
    }

    public function setVacationAutoreply(?string $value): ShopInterface
    {
        $this->vacationAutoreply = $value;

        return $this;
    }

    public function setVacationMessage(?string $value): ShopInterface
    {
        $this->vacationMessage = $value;

        return $this;
    }
}
