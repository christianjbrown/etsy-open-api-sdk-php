<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingWithAssociations implements ListingWithAssociationsInterface
{
    private ?ListingBuyerPriceInterface $buyerPrice = null;
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

    /**
     * @var array<int, ListingImageInterface>
     */
    private array $images = [];
    private ?ListingInventoryInterface $inventory = null;
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
    private ?ListingPersonalizationInterface $personalization = null;
    private ?MoneyInterface $price = null;
    private ?int $processingMax = null;
    private ?int $processingMin = null;

    /**
     * @var array<int, ShopProductionPartnerInterface>
     */
    private array $productionPartners = [];
    private ?int $quantity = null;
    private ?int $readinessStateId = null;
    private ?int $returnPolicyId = null;
    private ?string $richDescription = null;
    private ?ShopShippingProfileInterface $shippingProfile = null;
    private ?int $shippingProfileId = null;
    private ?ShopInterface $shop = null;
    private ?int $shopId = null;
    private ?int $shopSectionId = null;
    private ?bool $shouldAutoRenew = null;

    /**
     * @var array<int, string>
     */
    private array $skus = [];
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

    /**
     * @var array<int|string, ListingTranslationInterface>
     */
    private array $translations = [];
    private ?int $updatedTimestamp = null;
    private ?string $url = null;
    private ?UserInterface $user = null;
    private ?int $userId = null;

    /**
     * @var array<int, ListingVideoInterface>
     */
    private array $videos = [];
    private ?int $views = null;
    private ?string $whenMade = null;
    private ?string $whoMade = null;

    public function __construct(int $listingId)
    {
        $this->listingId = $listingId;
    }

    public function getBuyerPrice(): ?ListingBuyerPriceInterface
    {
        return $this->buyerPrice;
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

    /**
     * @return array<int, ListingImageInterface>
     */
    public function getImages(): array
    {
        return $this->images;
    }

    public function getInventory(): ?ListingInventoryInterface
    {
        return $this->inventory;
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

    public function getPersonalization(): ?ListingPersonalizationInterface
    {
        return $this->personalization;
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

    /**
     * @return array<int, ShopProductionPartnerInterface>
     */
    public function getProductionPartners(): array
    {
        return $this->productionPartners;
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

    public function getShippingProfile(): ?ShopShippingProfileInterface
    {
        return $this->shippingProfile;
    }

    public function getShippingProfileId(): ?int
    {
        return $this->shippingProfileId;
    }

    public function getShop(): ?ShopInterface
    {
        return $this->shop;
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

    /**
     * @return array<int, string>
     */
    public function getSkus(): array
    {
        return $this->skus;
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

    /**
     * @return array<int|string, ListingTranslationInterface>
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function getUpdatedTimestamp(): ?int
    {
        return $this->updatedTimestamp;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @return array<int, ListingVideoInterface>
     */
    public function getVideos(): array
    {
        return $this->videos;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function getWhenMade(): ?string
    {
        return $this->whenMade;
    }

    public function getWhoMade(): ?string
    {
        return $this->whoMade;
    }

    public function setBuyerPrice(?ListingBuyerPriceInterface $value): ListingWithAssociationsInterface
    {
        $this->buyerPrice = $value;

        return $this;
    }

    public function setConvertedPrice(?MoneyInterface $value): ListingWithAssociationsInterface
    {
        $this->convertedPrice = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): ListingWithAssociationsInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCreationTimestamp(?int $value): ListingWithAssociationsInterface
    {
        $this->creationTimestamp = $value;

        return $this;
    }

    public function setDescription(?string $value): ListingWithAssociationsInterface
    {
        $this->description = $value;

        return $this;
    }

    public function setEcgtAfterSalesServiceInfo(?string $value): ListingWithAssociationsInterface
    {
        $this->ecgtAfterSalesServiceInfo = $value;

        return $this;
    }

    public function setEcgtCommercialGuaranteeEnabled(?bool $value): ListingWithAssociationsInterface
    {
        $this->ecgtCommercialGuaranteeEnabled = $value;

        return $this;
    }

    public function setEcgtGaranBrand(?string $value): ListingWithAssociationsInterface
    {
        $this->ecgtGaranBrand = $value;

        return $this;
    }

    public function setEcgtGaranGuaranteeDetails(?string $value): ListingWithAssociationsInterface
    {
        $this->ecgtGaranGuaranteeDetails = $value;

        return $this;
    }

    public function setEcgtGaranModel(?string $value): ListingWithAssociationsInterface
    {
        $this->ecgtGaranModel = $value;

        return $this;
    }

    public function setEcgtGaranYears(?int $value): ListingWithAssociationsInterface
    {
        $this->ecgtGaranYears = $value;

        return $this;
    }

    public function setEcgtOtherCommercialGuaranteeDetails(?string $value): ListingWithAssociationsInterface
    {
        $this->ecgtOtherCommercialGuaranteeDetails = $value;

        return $this;
    }

    public function setEcgtSoftwareUpdateDetails(?string $value): ListingWithAssociationsInterface
    {
        $this->ecgtSoftwareUpdateDetails = $value;

        return $this;
    }

    public function setEndingTimestamp(?int $value): ListingWithAssociationsInterface
    {
        $this->endingTimestamp = $value;

        return $this;
    }

    public function setFeaturedRank(?int $value): ListingWithAssociationsInterface
    {
        $this->featuredRank = $value;

        return $this;
    }

    public function setFileData(?string $value): ListingWithAssociationsInterface
    {
        $this->fileData = $value;

        return $this;
    }

    public function setHasVariations(?bool $value): ListingWithAssociationsInterface
    {
        $this->hasVariations = $value;

        return $this;
    }

    /**
     * @param array<int, ListingImageInterface> $value
     */
    public function setImages(array $value): ListingWithAssociationsInterface
    {
        $this->images = $value;

        return $this;
    }

    public function setInventory(?ListingInventoryInterface $value): ListingWithAssociationsInterface
    {
        $this->inventory = $value;

        return $this;
    }

    public function setIsCustomizable(?bool $value): ListingWithAssociationsInterface
    {
        $this->isCustomizable = $value;

        return $this;
    }

    public function setIsPersonalizable(?bool $value): ListingWithAssociationsInterface
    {
        $this->isPersonalizable = $value;

        return $this;
    }

    public function setIsPrivate(?bool $value): ListingWithAssociationsInterface
    {
        $this->isPrivate = $value;

        return $this;
    }

    public function setIsSupply(?bool $value): ListingWithAssociationsInterface
    {
        $this->isSupply = $value;

        return $this;
    }

    public function setIsTaxable(?bool $value): ListingWithAssociationsInterface
    {
        $this->isTaxable = $value;

        return $this;
    }

    public function setItemDimensionsUnit(?string $value): ListingWithAssociationsInterface
    {
        $this->itemDimensionsUnit = $value;

        return $this;
    }

    public function setItemHeight(?float $value): ListingWithAssociationsInterface
    {
        $this->itemHeight = $value;

        return $this;
    }

    public function setItemLength(?float $value): ListingWithAssociationsInterface
    {
        $this->itemLength = $value;

        return $this;
    }

    public function setItemWeight(?float $value): ListingWithAssociationsInterface
    {
        $this->itemWeight = $value;

        return $this;
    }

    public function setItemWeightUnit(?string $value): ListingWithAssociationsInterface
    {
        $this->itemWeightUnit = $value;

        return $this;
    }

    public function setItemWidth(?float $value): ListingWithAssociationsInterface
    {
        $this->itemWidth = $value;

        return $this;
    }

    public function setLanguage(?string $value): ListingWithAssociationsInterface
    {
        $this->language = $value;

        return $this;
    }

    public function setLastModifiedTimestamp(?int $value): ListingWithAssociationsInterface
    {
        $this->lastModifiedTimestamp = $value;

        return $this;
    }

    public function setListingId(int $value): ListingWithAssociationsInterface
    {
        $this->listingId = $value;

        return $this;
    }

    public function setListingType(?string $value): ListingWithAssociationsInterface
    {
        $this->listingType = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setMaterials(array $value): ListingWithAssociationsInterface
    {
        $this->materials = $value;

        return $this;
    }

    public function setNonTaxable(?bool $value): ListingWithAssociationsInterface
    {
        $this->nonTaxable = $value;

        return $this;
    }

    public function setNumFavorers(?int $value): ListingWithAssociationsInterface
    {
        $this->numFavorers = $value;

        return $this;
    }

    public function setOriginalCreationTimestamp(?int $value): ListingWithAssociationsInterface
    {
        $this->originalCreationTimestamp = $value;

        return $this;
    }

    public function setPersonalization(?ListingPersonalizationInterface $value): ListingWithAssociationsInterface
    {
        $this->personalization = $value;

        return $this;
    }

    public function setPrice(?MoneyInterface $value): ListingWithAssociationsInterface
    {
        $this->price = $value;

        return $this;
    }

    public function setProcessingMax(?int $value): ListingWithAssociationsInterface
    {
        $this->processingMax = $value;

        return $this;
    }

    public function setProcessingMin(?int $value): ListingWithAssociationsInterface
    {
        $this->processingMin = $value;

        return $this;
    }

    /**
     * @param array<int, ShopProductionPartnerInterface> $value
     */
    public function setProductionPartners(array $value): ListingWithAssociationsInterface
    {
        $this->productionPartners = $value;

        return $this;
    }

    public function setQuantity(?int $value): ListingWithAssociationsInterface
    {
        $this->quantity = $value;

        return $this;
    }

    public function setReadinessStateId(?int $value): ListingWithAssociationsInterface
    {
        $this->readinessStateId = $value;

        return $this;
    }

    public function setReturnPolicyId(?int $value): ListingWithAssociationsInterface
    {
        $this->returnPolicyId = $value;

        return $this;
    }

    public function setRichDescription(?string $value): ListingWithAssociationsInterface
    {
        $this->richDescription = $value;

        return $this;
    }

    public function setShippingProfile(?ShopShippingProfileInterface $value): ListingWithAssociationsInterface
    {
        $this->shippingProfile = $value;

        return $this;
    }

    public function setShippingProfileId(?int $value): ListingWithAssociationsInterface
    {
        $this->shippingProfileId = $value;

        return $this;
    }

    public function setShop(?ShopInterface $value): ListingWithAssociationsInterface
    {
        $this->shop = $value;

        return $this;
    }

    public function setShopId(?int $value): ListingWithAssociationsInterface
    {
        $this->shopId = $value;

        return $this;
    }

    public function setShopSectionId(?int $value): ListingWithAssociationsInterface
    {
        $this->shopSectionId = $value;

        return $this;
    }

    public function setShouldAutoRenew(?bool $value): ListingWithAssociationsInterface
    {
        $this->shouldAutoRenew = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setSkus(array $value): ListingWithAssociationsInterface
    {
        $this->skus = $value;

        return $this;
    }

    public function setState(?string $value): ListingWithAssociationsInterface
    {
        $this->state = $value;

        return $this;
    }

    public function setStateTimestamp(?int $value): ListingWithAssociationsInterface
    {
        $this->stateTimestamp = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setStyle(array $value): ListingWithAssociationsInterface
    {
        $this->style = $value;

        return $this;
    }

    public function setSuggestedTitle(?string $value): ListingWithAssociationsInterface
    {
        $this->suggestedTitle = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): ListingWithAssociationsInterface
    {
        $this->tags = $value;

        return $this;
    }

    public function setTaxonomyId(?int $value): ListingWithAssociationsInterface
    {
        $this->taxonomyId = $value;

        return $this;
    }

    public function setTitle(?string $value): ListingWithAssociationsInterface
    {
        $this->title = $value;

        return $this;
    }

    /**
     * @param array<int|string, ListingTranslationInterface> $value
     */
    public function setTranslations(array $value): ListingWithAssociationsInterface
    {
        $this->translations = $value;

        return $this;
    }

    public function setUpdatedTimestamp(?int $value): ListingWithAssociationsInterface
    {
        $this->updatedTimestamp = $value;

        return $this;
    }

    public function setUrl(?string $value): ListingWithAssociationsInterface
    {
        $this->url = $value;

        return $this;
    }

    public function setUser(?UserInterface $value): ListingWithAssociationsInterface
    {
        $this->user = $value;

        return $this;
    }

    public function setUserId(?int $value): ListingWithAssociationsInterface
    {
        $this->userId = $value;

        return $this;
    }

    /**
     * @param array<int, ListingVideoInterface> $value
     */
    public function setVideos(array $value): ListingWithAssociationsInterface
    {
        $this->videos = $value;

        return $this;
    }

    public function setViews(?int $value): ListingWithAssociationsInterface
    {
        $this->views = $value;

        return $this;
    }

    public function setWhenMade(?string $value): ListingWithAssociationsInterface
    {
        $this->whenMade = $value;

        return $this;
    }

    public function setWhoMade(?string $value): ListingWithAssociationsInterface
    {
        $this->whoMade = $value;

        return $this;
    }
}
