<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of a `createDraftListing` call.
 */
interface CreateDraftListingRequestInterface
{
    public function getDescription(): string;

    /**
     * @return array<int, int>
     */
    public function getImageIds(): array;

    public function getIsCustomizable(): ?bool;

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

    public function getPrice(): float;

    public function getProcessingMax(): ?int;

    public function getProcessingMin(): ?int;

    /**
     * @return array<int, int>
     */
    public function getProductionPartnerIds(): array;

    public function getQuantity(): int;

    public function getReadinessStateId(): ?int;

    public function getReturnPolicyId(): ?int;

    public function getShippingProfileId(): ?int;

    public function getShopSectionId(): ?int;

    public function getShouldAutoRenew(): ?bool;

    /**
     * @return array<int, string>
     */
    public function getStyles(): array;

    /**
     * @return array<int, string>
     */
    public function getTags(): array;

    public function getTaxonomyId(): int;

    public function getTitle(): string;

    public function getType(): ?string;

    public function getWhenMade(): string;

    public function getWhoMade(): string;

    public function setDescription(string $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setImageIds(array $value): self;

    public function setIsCustomizable(?bool $value): self;

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

    public function setPrice(float $value): self;

    public function setProcessingMax(?int $value): self;

    public function setProcessingMin(?int $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setProductionPartnerIds(array $value): self;

    public function setQuantity(int $value): self;

    public function setReadinessStateId(?int $value): self;

    public function setReturnPolicyId(?int $value): self;

    public function setShippingProfileId(?int $value): self;

    public function setShopSectionId(?int $value): self;

    public function setShouldAutoRenew(?bool $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setStyles(array $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): self;

    public function setTaxonomyId(int $value): self;

    public function setTitle(string $value): self;

    public function setType(?string $value): self;

    public function setWhenMade(string $value): self;

    public function setWhoMade(string $value): self;
}
