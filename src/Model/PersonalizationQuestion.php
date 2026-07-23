<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class PersonalizationQuestion implements PersonalizationQuestionInterface
{
    private ?MoneyInterface $addOnPrice = null;
    private ?string $instructions = null;
    private ?int $maxAllowedCharacters = null;
    private ?int $maxAllowedFiles = null;

    /**
     * @var array<int, PersonalizationQuestionOptionInterface>
     */
    private array $options = [];
    private ?int $questionId = null;
    private ?string $questionText = null;
    private ?string $questionType = null;
    private ?bool $required = null;

    public function getAddOnPrice(): ?MoneyInterface
    {
        return $this->addOnPrice;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function getMaxAllowedCharacters(): ?int
    {
        return $this->maxAllowedCharacters;
    }

    public function getMaxAllowedFiles(): ?int
    {
        return $this->maxAllowedFiles;
    }

    /**
     * @return array<int, PersonalizationQuestionOptionInterface>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function getQuestionId(): ?int
    {
        return $this->questionId;
    }

    public function getQuestionText(): ?string
    {
        return $this->questionText;
    }

    public function getQuestionType(): ?string
    {
        return $this->questionType;
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function setAddOnPrice(?MoneyInterface $value): PersonalizationQuestionInterface
    {
        $this->addOnPrice = $value;

        return $this;
    }

    public function setInstructions(?string $value): PersonalizationQuestionInterface
    {
        $this->instructions = $value;

        return $this;
    }

    public function setMaxAllowedCharacters(?int $value): PersonalizationQuestionInterface
    {
        $this->maxAllowedCharacters = $value;

        return $this;
    }

    public function setMaxAllowedFiles(?int $value): PersonalizationQuestionInterface
    {
        $this->maxAllowedFiles = $value;

        return $this;
    }

    /**
     * @param array<int, PersonalizationQuestionOptionInterface> $value
     */
    public function setOptions(array $value): PersonalizationQuestionInterface
    {
        $this->options = $value;

        return $this;
    }

    public function setQuestionId(?int $value): PersonalizationQuestionInterface
    {
        $this->questionId = $value;

        return $this;
    }

    public function setQuestionText(?string $value): PersonalizationQuestionInterface
    {
        $this->questionText = $value;

        return $this;
    }

    public function setQuestionType(?string $value): PersonalizationQuestionInterface
    {
        $this->questionType = $value;

        return $this;
    }

    public function setRequired(?bool $value): PersonalizationQuestionInterface
    {
        $this->required = $value;

        return $this;
    }
}
