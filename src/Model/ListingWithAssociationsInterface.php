<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingWithAssociationsInterface
{
    public function getBuyerPrice(): ?ListingBuyerPriceInterface;

    public function getConvertedPrice(): ?MoneyInterface;

    public function getCreatedTimestamp(): ?int;

    public function getCreationTimestamp(): ?int;

    public function getDescription(): ?string;

    public function getEcgtAfterSalesServiceInfo(): ?string;

    public function getEcgtCommercialGuaranteeEnabled(): ?bool;

    public function getEcgtGaranBrand(): ?string;

    public function getEcgtGaranGuaranteeDetails(): ?string;

    public function getEcgtGaranModel(): ?string;

    public function getEcgtGaranYears(): ?int;

    public function getEcgtOtherCommercialGuaranteeDetails(): ?string;

    public function getEcgtSoftwareUpdateDetails(): ?string;

    public function getEndingTimestamp(): ?int;

    public function getFeaturedRank(): ?int;

    public function getFileData(): ?string;

    public function getHasVariations(): ?bool;

    /**
     * @return array<int, ListingImageInterface>
     */
    public function getImages(): array;

    public function getInventory(): ?ListingInventoryInterface;

    public function getIsCustomizable(): ?bool;

    public function getIsPersonalizable(): ?bool;

    public function getIsPrivate(): ?bool;

    public function getIsSupply(): ?bool;

    public function getIsTaxable(): ?bool;

    public function getItemDimensionsUnit(): ?string;

    public function getItemHeight(): ?float;

    public function getItemLength(): ?float;

    public function getItemWeight(): ?float;

    public function getItemWeightUnit(): ?string;

    public function getItemWidth(): ?float;

    public function getLanguage(): ?string;

    public function getLastModifiedTimestamp(): ?int;

    public function getListingId(): int;

    public function getListingType(): ?string;

    /**
     * @return array<int, string>
     */
    public function getMaterials(): array;

    public function getNonTaxable(): ?bool;

    public function getNumFavorers(): ?int;

    public function getOriginalCreationTimestamp(): ?int;

    public function getPersonalization(): ?ListingPersonalizationInterface;

    public function getPrice(): ?MoneyInterface;

    public function getProcessingMax(): ?int;

    public function getProcessingMin(): ?int;

    /**
     * @return array<int, ShopProductionPartnerInterface>
     */
    public function getProductionPartners(): array;

    public function getQuantity(): ?int;

    public function getReadinessStateId(): ?int;

    public function getReturnPolicyId(): ?int;

    public function getRichDescription(): ?string;

    public function getShippingProfile(): ?ShopShippingProfileInterface;

    public function getShippingProfileId(): ?int;

    public function getShop(): ?ShopInterface;

    public function getShopId(): ?int;

    public function getShopSectionId(): ?int;

    public function getShouldAutoRenew(): ?bool;

    /**
     * @return array<int, string>
     */
    public function getSkus(): array;

    public function getState(): ?string;

    public function getStateTimestamp(): ?int;

    /**
     * @return array<int, string>
     */
    public function getStyle(): array;

    public function getSuggestedTitle(): ?string;

    /**
     * @return array<int, string>
     */
    public function getTags(): array;

    public function getTaxonomyId(): ?int;

    public function getTitle(): ?string;

    /**
     * @return array<int|string, ListingTranslationInterface>
     */
    public function getTranslations(): array;

    public function getUpdatedTimestamp(): ?int;

    public function getUrl(): ?string;

    public function getUser(): ?UserInterface;

    public function getUserId(): ?int;

    /**
     * @return array<int, ListingVideoInterface>
     */
    public function getVideos(): array;

    public function getViews(): ?int;

    public function getWhenMade(): ?string;

    public function getWhoMade(): ?string;

    public function setBuyerPrice(?ListingBuyerPriceInterface $value): self;

    public function setConvertedPrice(?MoneyInterface $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setCreationTimestamp(?int $value): self;

    public function setDescription(?string $value): self;

    public function setEcgtAfterSalesServiceInfo(?string $value): self;

    public function setEcgtCommercialGuaranteeEnabled(?bool $value): self;

    public function setEcgtGaranBrand(?string $value): self;

    public function setEcgtGaranGuaranteeDetails(?string $value): self;

    public function setEcgtGaranModel(?string $value): self;

    public function setEcgtGaranYears(?int $value): self;

    public function setEcgtOtherCommercialGuaranteeDetails(?string $value): self;

    public function setEcgtSoftwareUpdateDetails(?string $value): self;

    public function setEndingTimestamp(?int $value): self;

    public function setFeaturedRank(?int $value): self;

    public function setFileData(?string $value): self;

    public function setHasVariations(?bool $value): self;

    /**
     * @param array<int, ListingImageInterface> $value
     */
    public function setImages(array $value): self;

    public function setInventory(?ListingInventoryInterface $value): self;

    public function setIsCustomizable(?bool $value): self;

    public function setIsPersonalizable(?bool $value): self;

    public function setIsPrivate(?bool $value): self;

    public function setIsSupply(?bool $value): self;

    public function setIsTaxable(?bool $value): self;

    public function setItemDimensionsUnit(?string $value): self;

    public function setItemHeight(?float $value): self;

    public function setItemLength(?float $value): self;

    public function setItemWeight(?float $value): self;

    public function setItemWeightUnit(?string $value): self;

    public function setItemWidth(?float $value): self;

    public function setLanguage(?string $value): self;

    public function setLastModifiedTimestamp(?int $value): self;

    public function setListingId(int $value): self;

    public function setListingType(?string $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setMaterials(array $value): self;

    public function setNonTaxable(?bool $value): self;

    public function setNumFavorers(?int $value): self;

    public function setOriginalCreationTimestamp(?int $value): self;

    public function setPersonalization(?ListingPersonalizationInterface $value): self;

    public function setPrice(?MoneyInterface $value): self;

    public function setProcessingMax(?int $value): self;

    public function setProcessingMin(?int $value): self;

    /**
     * @param array<int, ShopProductionPartnerInterface> $value
     */
    public function setProductionPartners(array $value): self;

    public function setQuantity(?int $value): self;

    public function setReadinessStateId(?int $value): self;

    public function setReturnPolicyId(?int $value): self;

    public function setRichDescription(?string $value): self;

    public function setShippingProfile(?ShopShippingProfileInterface $value): self;

    public function setShippingProfileId(?int $value): self;

    public function setShop(?ShopInterface $value): self;

    public function setShopId(?int $value): self;

    public function setShopSectionId(?int $value): self;

    public function setShouldAutoRenew(?bool $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setSkus(array $value): self;

    public function setState(?string $value): self;

    public function setStateTimestamp(?int $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setStyle(array $value): self;

    public function setSuggestedTitle(?string $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): self;

    public function setTaxonomyId(?int $value): self;

    public function setTitle(?string $value): self;

    /**
     * @param array<int|string, ListingTranslationInterface> $value
     */
    public function setTranslations(array $value): self;

    public function setUpdatedTimestamp(?int $value): self;

    public function setUrl(?string $value): self;

    public function setUser(?UserInterface $value): self;

    public function setUserId(?int $value): self;

    /**
     * @param array<int, ListingVideoInterface> $value
     */
    public function setVideos(array $value): self;

    public function setViews(?int $value): self;

    public function setWhenMade(?string $value): self;

    public function setWhoMade(?string $value): self;
}
