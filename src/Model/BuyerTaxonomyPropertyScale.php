<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class BuyerTaxonomyPropertyScale implements BuyerTaxonomyPropertyScaleInterface
{
    private ?string $description = null;
    private ?string $displayName = null;
    private ?int $scaleId = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getScaleId(): ?int
    {
        return $this->scaleId;
    }

    public function setDescription(?string $value): BuyerTaxonomyPropertyScaleInterface
    {
        $this->description = $value;

        return $this;
    }

    public function setDisplayName(?string $value): BuyerTaxonomyPropertyScaleInterface
    {
        $this->displayName = $value;

        return $this;
    }

    public function setScaleId(?int $value): BuyerTaxonomyPropertyScaleInterface
    {
        $this->scaleId = $value;

        return $this;
    }
}
