<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class BuyerTaxonomyPropertyValue implements BuyerTaxonomyPropertyValueInterface
{
    /**
     * @var array<int, int>
     */
    private array $equalTo = [];
    private ?string $name = null;
    private ?int $scaleId = null;
    private ?int $valueId = null;

    /**
     * @return array<int, int>
     */
    public function getEqualTo(): array
    {
        return $this->equalTo;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getScaleId(): ?int
    {
        return $this->scaleId;
    }

    public function getValueId(): ?int
    {
        return $this->valueId;
    }

    /**
     * @param array<int, int> $value
     */
    public function setEqualTo(array $value): BuyerTaxonomyPropertyValueInterface
    {
        $this->equalTo = $value;

        return $this;
    }

    public function setName(?string $value): BuyerTaxonomyPropertyValueInterface
    {
        $this->name = $value;

        return $this;
    }

    public function setScaleId(?int $value): BuyerTaxonomyPropertyValueInterface
    {
        $this->scaleId = $value;

        return $this;
    }

    public function setValueId(?int $value): BuyerTaxonomyPropertyValueInterface
    {
        $this->valueId = $value;

        return $this;
    }
}
