<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class PersonalizationQuestionOptionRequest implements PersonalizationQuestionOptionRequestInterface
{
    private string $label;
    private ?int $optionId = null;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getOptionId(): ?int
    {
        return $this->optionId;
    }

    public function setLabel(string $value): PersonalizationQuestionOptionRequestInterface
    {
        $this->label = $value;

        return $this;
    }

    public function setOptionId(?int $value): PersonalizationQuestionOptionRequestInterface
    {
        $this->optionId = $value;

        return $this;
    }
}
