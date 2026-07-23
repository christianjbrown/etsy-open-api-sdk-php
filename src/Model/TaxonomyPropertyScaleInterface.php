<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface TaxonomyPropertyScaleInterface
{
    public function getDescription(): ?string;

    public function getDisplayName(): ?string;

    public function getScaleId(): ?int;

    public function setDescription(?string $value): self;

    public function setDisplayName(?string $value): self;

    public function setScaleId(?int $value): self;
}
