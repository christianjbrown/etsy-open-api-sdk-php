<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingPropertyValueInterface
{
    public function getPropertyId(): ?int;

    public function getPropertyName(): ?string;

    public function getScaleId(): ?int;

    public function getScaleName(): ?string;

    /**
     * @return array<int, int>
     */
    public function getValueIds(): array;

    /**
     * @return array<int, string>
     */
    public function getValues(): array;

    public function setPropertyId(?int $value): self;

    public function setPropertyName(?string $value): self;

    public function setScaleId(?int $value): self;

    public function setScaleName(?string $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setValueIds(array $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setValues(array $value): self;
}
