<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class TaxonomyPropertyValue implements TaxonomyPropertyValueInterface
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
    public function setEqualTo(array $value): TaxonomyPropertyValueInterface
    {
        $this->equalTo = $value;

        return $this;
    }

    public function setName(?string $value): TaxonomyPropertyValueInterface
    {
        $this->name = $value;

        return $this;
    }

    public function setScaleId(?int $value): TaxonomyPropertyValueInterface
    {
        $this->scaleId = $value;

        return $this;
    }

    public function setValueId(?int $value): TaxonomyPropertyValueInterface
    {
        $this->valueId = $value;

        return $this;
    }
}
