<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class PersonalizationQuestionRequest implements PersonalizationQuestionRequestInterface
{
    private ?float $addOnPrice = null;
    private ?string $instructions = null;
    private ?int $maxAllowedCharacters = null;
    private ?int $maxAllowedFiles = null;

    /**
     * @var array<int, PersonalizationQuestionOptionRequestInterface>
     */
    private array $options = [];
    private ?int $questionId = null;
    private string $questionText;
    private string $questionType;
    private bool $required;

    public function __construct(string $questionText, string $questionType, bool $required)
    {
        $this->questionText = $questionText;
        $this->questionType = $questionType;
        $this->required = $required;
    }

    public function getAddOnPrice(): ?float
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
     * @return array<int, PersonalizationQuestionOptionRequestInterface>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function getQuestionId(): ?int
    {
        return $this->questionId;
    }

    public function getQuestionText(): string
    {
        return $this->questionText;
    }

    public function getQuestionType(): string
    {
        return $this->questionType;
    }

    public function getRequired(): bool
    {
        return $this->required;
    }

    public function setAddOnPrice(?float $value): PersonalizationQuestionRequestInterface
    {
        $this->addOnPrice = $value;

        return $this;
    }

    public function setInstructions(?string $value): PersonalizationQuestionRequestInterface
    {
        $this->instructions = $value;

        return $this;
    }

    public function setMaxAllowedCharacters(?int $value): PersonalizationQuestionRequestInterface
    {
        $this->maxAllowedCharacters = $value;

        return $this;
    }

    public function setMaxAllowedFiles(?int $value): PersonalizationQuestionRequestInterface
    {
        $this->maxAllowedFiles = $value;

        return $this;
    }

    /**
     * @param array<int, PersonalizationQuestionOptionRequestInterface> $value
     */
    public function setOptions(array $value): PersonalizationQuestionRequestInterface
    {
        $this->options = $value;

        return $this;
    }

    public function setQuestionId(?int $value): PersonalizationQuestionRequestInterface
    {
        $this->questionId = $value;

        return $this;
    }

    public function setQuestionText(string $value): PersonalizationQuestionRequestInterface
    {
        $this->questionText = $value;

        return $this;
    }

    public function setQuestionType(string $value): PersonalizationQuestionRequestInterface
    {
        $this->questionType = $value;

        return $this;
    }

    public function setRequired(bool $value): PersonalizationQuestionRequestInterface
    {
        $this->required = $value;

        return $this;
    }
}
