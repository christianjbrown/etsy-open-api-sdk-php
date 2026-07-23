<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class BuyerTaxonomyNodeProperty implements BuyerTaxonomyNodePropertyInterface
{
    private ?string $displayName = null;
    private ?bool $isMultivalued = null;
    private ?bool $isRequired = null;
    private ?int $maxValuesAllowed = null;
    private ?string $name = null;

    /**
     * @var array<int, BuyerTaxonomyPropertyValueInterface>
     */
    private array $possibleValues = [];
    private int $propertyId;

    /**
     * @var array<int, BuyerTaxonomyPropertyScaleInterface>
     */
    private array $scales = [];

    /**
     * @var array<int, BuyerTaxonomyPropertyValueInterface>
     */
    private array $selectedValues = [];
    private ?bool $supportsAttributes = null;
    private ?bool $supportsVariations = null;

    public function __construct(int $propertyId)
    {
        $this->propertyId = $propertyId;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getIsMultivalued(): ?bool
    {
        return $this->isMultivalued;
    }

    public function getIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function getMaxValuesAllowed(): ?int
    {
        return $this->maxValuesAllowed;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return array<int, BuyerTaxonomyPropertyValueInterface>
     */
    public function getPossibleValues(): array
    {
        return $this->possibleValues;
    }

    public function getPropertyId(): int
    {
        return $this->propertyId;
    }

    /**
     * @return array<int, BuyerTaxonomyPropertyScaleInterface>
     */
    public function getScales(): array
    {
        return $this->scales;
    }

    /**
     * @return array<int, BuyerTaxonomyPropertyValueInterface>
     */
    public function getSelectedValues(): array
    {
        return $this->selectedValues;
    }

    public function getSupportsAttributes(): ?bool
    {
        return $this->supportsAttributes;
    }

    public function getSupportsVariations(): ?bool
    {
        return $this->supportsVariations;
    }

    public function setDisplayName(?string $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->displayName = $value;

        return $this;
    }

    public function setIsMultivalued(?bool $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->isMultivalued = $value;

        return $this;
    }

    public function setIsRequired(?bool $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->isRequired = $value;

        return $this;
    }

    public function setMaxValuesAllowed(?int $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->maxValuesAllowed = $value;

        return $this;
    }

    public function setName(?string $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @param array<int, BuyerTaxonomyPropertyValueInterface> $value
     */
    public function setPossibleValues(array $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->possibleValues = $value;

        return $this;
    }

    public function setPropertyId(int $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->propertyId = $value;

        return $this;
    }

    /**
     * @param array<int, BuyerTaxonomyPropertyScaleInterface> $value
     */
    public function setScales(array $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->scales = $value;

        return $this;
    }

    /**
     * @param array<int, BuyerTaxonomyPropertyValueInterface> $value
     */
    public function setSelectedValues(array $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->selectedValues = $value;

        return $this;
    }

    public function setSupportsAttributes(?bool $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->supportsAttributes = $value;

        return $this;
    }

    public function setSupportsVariations(?bool $value): BuyerTaxonomyNodePropertyInterface
    {
        $this->supportsVariations = $value;

        return $this;
    }
}
