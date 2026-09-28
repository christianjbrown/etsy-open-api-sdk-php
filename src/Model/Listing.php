<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Listing implements ListingInterface
{
    private ?MoneyInterface $convertedPrice = null;
    private ?int $createdTimestamp = null;
    private ?int $creationTimestamp = null;
    private ?string $description = null;
    private ?string $ecgtAfterSalesServiceInfo = null;
    private ?bool $ecgtCommercialGuaranteeEnabled = null;
    private ?string $ecgtGaranBrand = null;
    private ?string $ecgtGaranGuaranteeDetails = null;
    private ?string $ecgtGaranModel = null;
    private ?int $ecgtGaranYears = null;
    private ?string $ecgtOtherCommercialGuaranteeDetails = null;
    private ?string $ecgtSoftwareUpdateDetails = null;
    private ?int $endingTimestamp = null;
    private ?int $featuredRank = null;
    private ?string $fileData = null;
    private ?bool $hasVariations = null;
    private ?bool $isCustomizable = null;
    private ?bool $isPersonalizable = null;
    private ?bool $isPrivate = null;
    private ?bool $isSupply = null;
    private ?bool $isTaxable = null;
    private ?string $itemDimensionsUnit = null;
    private ?float $itemHeight = null;
    private ?float $itemLength = null;
    private ?float $itemWeight = null;
    private ?string $itemWeightUnit = null;
    private ?float $itemWidth = null;
    private ?string $language = null;
    private ?int $lastModifiedTimestamp = null;
    private int $listingId;
    private ?string $listingType = null;

    /**
     * @var array<int, string>
     */
    private array $materials = [];
    private ?bool $nonTaxable = null;
    private ?int $numFavorers = null;
    private ?int $originalCreationTimestamp = null;
    private ?MoneyInterface $price = null;
    private ?int $processingMax = null;
    private ?int $processingMin = null;
    private ?int $quantity = null;
    private ?int $readinessStateId = null;
    private ?int $returnPolicyId = null;
    private ?string $richDescription = null;
    private ?int $shippingProfileId = null;
    private ?int $shopId = null;
    private ?int $shopSectionId = null;
    private ?bool $shouldAutoRenew = null;
    private ?string $state = null;
    private ?int $stateTimestamp = null;

    /**
     * @var array<int, string>
     */
    private array $style = [];
    private ?string $suggestedTitle = null;

    /**
     * @var array<int, string>
     */
    private array $tags = [];
    private ?int $taxonomyId = null;
    private ?string $title = null;
    private ?int $updatedTimestamp = null;
    private ?string $url = null;
    private ?int $userId = null;
    private ?string $whenMade = null;
    private ?string $whoMade = null;

    public function __construct(int $listingId)
    {
        $this->listingId = $listingId;
    }

    public function getConvertedPrice(): ?MoneyInterface
    {
        return $this->convertedPrice;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getCreationTimestamp(): ?int
    {
        return $this->creationTimestamp;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEcgtAfterSalesServiceInfo(): ?string
    {
        return $this->ecgtAfterSalesServiceInfo;
    }

    public function getEcgtCommercialGuaranteeEnabled(): ?bool
    {
        return $this->ecgtCommercialGuaranteeEnabled;
    }

    public function getEcgtGaranBrand(): ?string
    {
        return $this->ecgtGaranBrand;
    }

    public function getEcgtGaranGuaranteeDetails(): ?string
    {
        return $this->ecgtGaranGuaranteeDetails;
    }

    public function getEcgtGaranModel(): ?string
    {
        return $this->ecgtGaranModel;
    }

    public function getEcgtGaranYears(): ?int
    {
        return $this->ecgtGaranYears;
    }

    public function getEcgtOtherCommercialGuaranteeDetails(): ?string
    {
        return $this->ecgtOtherCommercialGuaranteeDetails;
    }

    public function getEcgtSoftwareUpdateDetails(): ?string
    {
        return $this->ecgtSoftwareUpdateDetails;
    }

    public function getEndingTimestamp(): ?int
    {
        return $this->endingTimestamp;
    }

    public function getFeaturedRank(): ?int
    {
        return $this->featuredRank;
    }

    public function getFileData(): ?string
    {
        return $this->fileData;
    }

    public function getHasVariations(): ?bool
    {
        return $this->hasVariations;
    }

    public function getIsCustomizable(): ?bool
    {
        return $this->isCustomizable;
    }

    public function getIsPersonalizable(): ?bool
    {
        return $this->isPersonalizable;
    }

    public function getIsPrivate(): ?bool
    {
        return $this->isPrivate;
    }

    public function getIsSupply(): ?bool
    {
        return $this->isSupply;
    }

    public function getIsTaxable(): ?bool
    {
        return $this->isTaxable;
    }

    public function getItemDimensionsUnit(): ?string
    {
        return $this->itemDimensionsUnit;
    }

    public function getItemHeight(): ?float
    {
        return $this->itemHeight;
    }

    public function getItemLength(): ?float
    {
        return $this->itemLength;
    }

    public function getItemWeight(): ?float
    {
        return $this->itemWeight;
    }

    public function getItemWeightUnit(): ?string
    {
        return $this->itemWeightUnit;
    }

    public function getItemWidth(): ?float
    {
        return $this->itemWidth;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getLastModifiedTimestamp(): ?int
    {
        return $this->lastModifiedTimestamp;
    }

    public function getListingId(): int
    {
        return $this->listingId;
    }

    public function getListingType(): ?string
    {
        return $this->listingType;
    }

    /**
     * @return array<int, string>
     */
    public function getMaterials(): array
    {
        return $this->materials;
    }

    public function getNonTaxable(): ?bool
    {
        return $this->nonTaxable;
    }

    public function getNumFavorers(): ?int
    {
        return $this->numFavorers;
    }

    public function getOriginalCreationTimestamp(): ?int
    {
        return $this->originalCreationTimestamp;
    }

    public function getPrice(): ?MoneyInterface
    {
        return $this->price;
    }

    public function getProcessingMax(): ?int
    {
        return $this->processingMax;
    }

    public function getProcessingMin(): ?int
    {
        return $this->processingMin;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getReadinessStateId(): ?int
    {
        return $this->readinessStateId;
    }

    public function getReturnPolicyId(): ?int
    {
        return $this->returnPolicyId;
    }

    public function getRichDescription(): ?string
    {
        return $this->richDescription;
    }

    public function getShippingProfileId(): ?int
    {
        return $this->shippingProfileId;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function getShopSectionId(): ?int
    {
        return $this->shopSectionId;
    }

    public function getShouldAutoRenew(): ?bool
    {
        return $this->shouldAutoRenew;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getStateTimestamp(): ?int
    {
        return $this->stateTimestamp;
    }

    /**
     * @return array<int, string>
     */
    public function getStyle(): array
    {
        return $this->style;
    }

    public function getSuggestedTitle(): ?string
    {
        return $this->suggestedTitle;
    }

    /**
     * @return array<int, string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getTaxonomyId(): ?int
    {
        return $this->taxonomyId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
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

    public function getWhenMade(): ?string
    {
        return $this->whenMade;
    }

    public function getWhoMade(): ?string
    {
        return $this->whoMade;
    }

    public function setConvertedPrice(?MoneyInterface $value): ListingInterface
    {
        $this->convertedPrice = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): ListingInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCreationTimestamp(?int $value): ListingInterface
    {
        $this->creationTimestamp = $value;

        return $this;
    }

    public function setDescription(?string $value): ListingInterface
    {
        $this->description = $value;

        return $this;
    }

    public function setEcgtAfterSalesServiceInfo(?string $value): ListingInterface
    {
        $this->ecgtAfterSalesServiceInfo = $value;

        return $this;
    }

    public function setEcgtCommercialGuaranteeEnabled(?bool $value): ListingInterface
    {
        $this->ecgtCommercialGuaranteeEnabled = $value;

        return $this;
    }

    public function setEcgtGaranBrand(?string $value): ListingInterface
    {
        $this->ecgtGaranBrand = $value;

        return $this;
    }

    public function setEcgtGaranGuaranteeDetails(?string $value): ListingInterface
    {
        $this->ecgtGaranGuaranteeDetails = $value;

        return $this;
    }

    public function setEcgtGaranModel(?string $value): ListingInterface
    {
        $this->ecgtGaranModel = $value;

        return $this;
    }

    public function setEcgtGaranYears(?int $value): ListingInterface
    {
        $this->ecgtGaranYears = $value;

        return $this;
    }

    public function setEcgtOtherCommercialGuaranteeDetails(?string $value): ListingInterface
    {
        $this->ecgtOtherCommercialGuaranteeDetails = $value;

        return $this;
    }

    public function setEcgtSoftwareUpdateDetails(?string $value): ListingInterface
    {
        $this->ecgtSoftwareUpdateDetails = $value;

        return $this;
    }

    public function setEndingTimestamp(?int $value): ListingInterface
    {
        $this->endingTimestamp = $value;

        return $this;
    }

    public function setFeaturedRank(?int $value): ListingInterface
    {
        $this->featuredRank = $value;

        return $this;
    }

    public function setFileData(?string $value): ListingInterface
    {
        $this->fileData = $value;

        return $this;
    }

    public function setHasVariations(?bool $value): ListingInterface
    {
        $this->hasVariations = $value;

        return $this;
    }

    public function setIsCustomizable(?bool $value): ListingInterface
    {
        $this->isCustomizable = $value;

        return $this;
    }

    public function setIsPersonalizable(?bool $value): ListingInterface
    {
        $this->isPersonalizable = $value;

        return $this;
    }

    public function setIsPrivate(?bool $value): ListingInterface
    {
        $this->isPrivate = $value;

        return $this;
    }

    public function setIsSupply(?bool $value): ListingInterface
    {
        $this->isSupply = $value;

        return $this;
    }

    public function setIsTaxable(?bool $value): ListingInterface
    {
        $this->isTaxable = $value;

        return $this;
    }

    public function setItemDimensionsUnit(?string $value): ListingInterface
    {
        $this->itemDimensionsUnit = $value;

        return $this;
    }

    public function setItemHeight(?float $value): ListingInterface
    {
        $this->itemHeight = $value;

        return $this;
    }

    public function setItemLength(?float $value): ListingInterface
    {
        $this->itemLength = $value;

        return $this;
    }

    public function setItemWeight(?float $value): ListingInterface
    {
        $this->itemWeight = $value;

        return $this;
    }

    public function setItemWeightUnit(?string $value): ListingInterface
    {
        $this->itemWeightUnit = $value;

        return $this;
    }

    public function setItemWidth(?float $value): ListingInterface
    {
        $this->itemWidth = $value;

        return $this;
    }

    public function setLanguage(?string $value): ListingInterface
    {
        $this->language = $value;

        return $this;
    }

    public function setLastModifiedTimestamp(?int $value): ListingInterface
    {
        $this->lastModifiedTimestamp = $value;

        return $this;
    }

    public function setListingId(int $value): ListingInterface
    {
        $this->listingId = $value;

        return $this;
    }

    public function setListingType(?string $value): ListingInterface
    {
        $this->listingType = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setMaterials(array $value): ListingInterface
    {
        $this->materials = $value;

        return $this;
    }

    public function setNonTaxable(?bool $value): ListingInterface
    {
        $this->nonTaxable = $value;

        return $this;
    }

    public function setNumFavorers(?int $value): ListingInterface
    {
        $this->numFavorers = $value;

        return $this;
    }

    public function setOriginalCreationTimestamp(?int $value): ListingInterface
    {
        $this->originalCreationTimestamp = $value;

        return $this;
    }

    public function setPrice(?MoneyInterface $value): ListingInterface
    {
        $this->price = $value;

        return $this;
    }

    public function setProcessingMax(?int $value): ListingInterface
    {
        $this->processingMax = $value;

        return $this;
    }

    public function setProcessingMin(?int $value): ListingInterface
    {
        $this->processingMin = $value;

        return $this;
    }

    public function setQuantity(?int $value): ListingInterface
    {
        $this->quantity = $value;

        return $this;
    }

    public function setReadinessStateId(?int $value): ListingInterface
    {
        $this->readinessStateId = $value;

        return $this;
    }

    public function setReturnPolicyId(?int $value): ListingInterface
    {
        $this->returnPolicyId = $value;

        return $this;
    }

    public function setRichDescription(?string $value): ListingInterface
    {
        $this->richDescription = $value;

        return $this;
    }

    public function setShippingProfileId(?int $value): ListingInterface
    {
        $this->shippingProfileId = $value;

        return $this;
    }

    public function setShopId(?int $value): ListingInterface
    {
        $this->shopId = $value;

        return $this;
    }

    public function setShopSectionId(?int $value): ListingInterface
    {
        $this->shopSectionId = $value;

        return $this;
    }

    public function setShouldAutoRenew(?bool $value): ListingInterface
    {
        $this->shouldAutoRenew = $value;

        return $this;
    }

    public function setState(?string $value): ListingInterface
    {
        $this->state = $value;

        return $this;
    }

    public function setStateTimestamp(?int $value): ListingInterface
    {
        $this->stateTimestamp = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setStyle(array $value): ListingInterface
    {
        $this->style = $value;

        return $this;
    }

    public function setSuggestedTitle(?string $value): ListingInterface
    {
        $this->suggestedTitle = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): ListingInterface
    {
        $this->tags = $value;

        return $this;
    }

    public function setTaxonomyId(?int $value): ListingInterface
    {
        $this->taxonomyId = $value;

        return $this;
    }

    public function setTitle(?string $value): ListingInterface
    {
        $this->title = $value;

        return $this;
    }

    public function setUpdatedTimestamp(?int $value): ListingInterface
    {
        $this->updatedTimestamp = $value;

        return $this;
    }

    public function setUrl(?string $value): ListingInterface
    {
        $this->url = $value;

        return $this;
    }

    public function setUserId(?int $value): ListingInterface
    {
        $this->userId = $value;

        return $this;
    }

    public function setWhenMade(?string $value): ListingInterface
    {
        $this->whenMade = $value;

        return $this;
    }

    public function setWhoMade(?string $value): ListingInterface
    {
        $this->whoMade = $value;

        return $this;
    }
}
