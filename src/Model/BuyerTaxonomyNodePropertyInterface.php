<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface BuyerTaxonomyNodePropertyInterface
{
    public function getDisplayName(): ?string;

    public function getIsMultivalued(): ?bool;

    public function getIsRequired(): ?bool;

    public function getMaxValuesAllowed(): ?int;

    public function getName(): ?string;

    /**
     * @return array<int, BuyerTaxonomyPropertyValueInterface>
     */
    public function getPossibleValues(): array;

    public function getPropertyId(): int;

    /**
     * @return array<int, BuyerTaxonomyPropertyScaleInterface>
     */
    public function getScales(): array;

    /**
     * @return array<int, BuyerTaxonomyPropertyValueInterface>
     */
    public function getSelectedValues(): array;

    public function getSupportsAttributes(): ?bool;

    public function getSupportsVariations(): ?bool;

    public function setDisplayName(?string $value): self;

    public function setIsMultivalued(?bool $value): self;

    public function setIsRequired(?bool $value): self;

    public function setMaxValuesAllowed(?int $value): self;

    public function setName(?string $value): self;

    /**
     * @param array<int, BuyerTaxonomyPropertyValueInterface> $value
     */
    public function setPossibleValues(array $value): self;

    public function setPropertyId(int $value): self;

    /**
     * @param array<int, BuyerTaxonomyPropertyScaleInterface> $value
     */
    public function setScales(array $value): self;

    /**
     * @param array<int, BuyerTaxonomyPropertyValueInterface> $value
     */
    public function setSelectedValues(array $value): self;

    public function setSupportsAttributes(?bool $value): self;

    public function setSupportsVariations(?bool $value): self;
}
