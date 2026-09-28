<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `updateListing` call. Every field is optional: only what is set is sent,
 * and anything left alone keeps its current value.
 */
interface UpdateListingRequestInterface
{
    public function getDescription(): ?string;

    public function getEcgtAfterSalesServiceInfo(): ?string;

    public function getEcgtGaranBrand(): ?string;

    public function getEcgtGaranGuaranteeDetails(): ?string;

    public function getEcgtGaranModel(): ?string;

    public function getEcgtGaranYears(): ?int;

    public function getEcgtOtherCommercialGuaranteeDetails(): ?string;

    public function getEcgtSoftwareUpdateDetails(): ?string;

    public function getFeaturedRank(): ?int;

    /**
     * @return array<int, int>
     */
    public function getImageIds(): array;

    public function getIsSupply(): ?bool;

    public function getIsTaxable(): ?bool;

    public function getItemDimensionsUnit(): ?string;

    public function getItemHeight(): ?float;

    public function getItemLength(): ?float;

    public function getItemWeight(): ?float;

    public function getItemWeightUnit(): ?string;

    public function getItemWidth(): ?float;

    /**
     * @return array<int, string>
     */
    public function getMaterials(): array;

    /**
     * @return array<int, int>
     */
    public function getProductionPartnerIds(): array;

    public function getReturnPolicyId(): ?int;

    public function getShippingProfileId(): ?int;

    public function getShopSectionId(): ?int;

    public function getShouldAutoRenew(): ?bool;

    public function getState(): ?string;

    /**
     * @return array<int, string>
     */
    public function getTags(): array;

    public function getTaxonomyId(): ?int;

    public function getTitle(): ?string;

    public function getType(): ?string;

    public function getWhenMade(): ?string;

    public function getWhoMade(): ?string;

    public function setDescription(?string $value): self;

    public function setEcgtAfterSalesServiceInfo(?string $value): self;

    public function setEcgtGaranBrand(?string $value): self;

    public function setEcgtGaranGuaranteeDetails(?string $value): self;

    public function setEcgtGaranModel(?string $value): self;

    public function setEcgtGaranYears(?int $value): self;

    public function setEcgtOtherCommercialGuaranteeDetails(?string $value): self;

    public function setEcgtSoftwareUpdateDetails(?string $value): self;

    public function setFeaturedRank(?int $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setImageIds(array $value): self;

    public function setIsSupply(?bool $value): self;

    public function setIsTaxable(?bool $value): self;

    public function setItemDimensionsUnit(?string $value): self;

    public function setItemHeight(?float $value): self;

    public function setItemLength(?float $value): self;

    public function setItemWeight(?float $value): self;

    public function setItemWeightUnit(?string $value): self;

    public function setItemWidth(?float $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setMaterials(array $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setProductionPartnerIds(array $value): self;

    public function setReturnPolicyId(?int $value): self;

    public function setShippingProfileId(?int $value): self;

    public function setShopSectionId(?int $value): self;

    public function setShouldAutoRenew(?bool $value): self;

    public function setState(?string $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): self;

    public function setTaxonomyId(?int $value): self;

    public function setTitle(?string $value): self;

    public function setType(?string $value): self;

    public function setWhenMade(?string $value): self;

    public function setWhoMade(?string $value): self;
}
