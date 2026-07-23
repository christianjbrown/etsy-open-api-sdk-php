<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface PersonalizationQuestionInterface
{
    public function getAddOnPrice(): ?MoneyInterface;

    public function getInstructions(): ?string;

    public function getMaxAllowedCharacters(): ?int;

    public function getMaxAllowedFiles(): ?int;

    /**
     * @return array<int, PersonalizationQuestionOptionInterface>
     */
    public function getOptions(): array;

    public function getQuestionId(): ?int;

    public function getQuestionText(): ?string;

    public function getQuestionType(): ?string;

    public function getRequired(): ?bool;

    public function setAddOnPrice(?MoneyInterface $value): self;

    public function setInstructions(?string $value): self;

    public function setMaxAllowedCharacters(?int $value): self;

    public function setMaxAllowedFiles(?int $value): self;

    /**
     * @param array<int, PersonalizationQuestionOptionInterface> $value
     */
    public function setOptions(array $value): self;

    public function setQuestionId(?int $value): self;

    public function setQuestionText(?string $value): self;

    public function setQuestionType(?string $value): self;

    public function setRequired(?bool $value): self;
}
