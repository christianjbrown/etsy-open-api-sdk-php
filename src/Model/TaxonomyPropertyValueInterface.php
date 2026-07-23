<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface TaxonomyPropertyValueInterface
{
    /**
     * @return array<int, int>
     */
    public function getEqualTo(): array;

    public function getName(): ?string;

    public function getScaleId(): ?int;

    public function getValueId(): ?int;

    /**
     * @param array<int, int> $value
     */
    public function setEqualTo(array $value): self;

    public function setName(?string $value): self;

    public function setScaleId(?int $value): self;

    public function setValueId(?int $value): self;
}
