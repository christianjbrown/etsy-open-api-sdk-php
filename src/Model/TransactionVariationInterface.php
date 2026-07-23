<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface TransactionVariationInterface
{
    public function getFormattedName(): ?string;

    public function getFormattedValue(): ?string;

    public function getPropertyId(): ?int;

    public function getQuestionId(): ?int;

    public function getValueId(): ?int;

    public function setFormattedName(?string $value): self;

    public function setFormattedValue(?string $value): self;

    public function setPropertyId(?int $value): self;

    public function setQuestionId(?int $value): self;

    public function setValueId(?int $value): self;
}
