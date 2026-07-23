<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class TaxonomyNodeProperty implements TaxonomyNodePropertyInterface
{
    private ?string $displayName = null;
    private ?bool $isMultivalued = null;
    private ?bool $isRequired = null;
    private ?int $maxValuesAllowed = null;
    private ?string $name = null;

    /**
     * @var array<int, TaxonomyPropertyValueInterface>
     */
    private array $possibleValues = [];
    private int $propertyId;

    /**
     * @var array<int, TaxonomyPropertyScaleInterface>
     */
    private array $scales = [];

    /**
     * @var array<int, TaxonomyPropertyValueInterface>
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
     * @return array<int, TaxonomyPropertyValueInterface>
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
     * @return array<int, TaxonomyPropertyScaleInterface>
     */
    public function getScales(): array
    {
        return $this->scales;
    }

    /**
     * @return array<int, TaxonomyPropertyValueInterface>
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

    public function setDisplayName(?string $value): TaxonomyNodePropertyInterface
    {
        $this->displayName = $value;

        return $this;
    }

    public function setIsMultivalued(?bool $value): TaxonomyNodePropertyInterface
    {
        $this->isMultivalued = $value;

        return $this;
    }

    public function setIsRequired(?bool $value): TaxonomyNodePropertyInterface
    {
        $this->isRequired = $value;

        return $this;
    }

    public function setMaxValuesAllowed(?int $value): TaxonomyNodePropertyInterface
    {
        $this->maxValuesAllowed = $value;

        return $this;
    }

    public function setName(?string $value): TaxonomyNodePropertyInterface
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @param array<int, TaxonomyPropertyValueInterface> $value
     */
    public function setPossibleValues(array $value): TaxonomyNodePropertyInterface
    {
        $this->possibleValues = $value;

        return $this;
    }

    public function setPropertyId(int $value): TaxonomyNodePropertyInterface
    {
        $this->propertyId = $value;

        return $this;
    }

    /**
     * @param array<int, TaxonomyPropertyScaleInterface> $value
     */
    public function setScales(array $value): TaxonomyNodePropertyInterface
    {
        $this->scales = $value;

        return $this;
    }

    /**
     * @param array<int, TaxonomyPropertyValueInterface> $value
     */
    public function setSelectedValues(array $value): TaxonomyNodePropertyInterface
    {
        $this->selectedValues = $value;

        return $this;
    }

    public function setSupportsAttributes(?bool $value): TaxonomyNodePropertyInterface
    {
        $this->supportsAttributes = $value;

        return $this;
    }

    public function setSupportsVariations(?bool $value): TaxonomyNodePropertyInterface
    {
        $this->supportsVariations = $value;

        return $this;
    }
}
