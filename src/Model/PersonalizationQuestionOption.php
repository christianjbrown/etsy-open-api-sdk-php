<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class PersonalizationQuestionOption implements PersonalizationQuestionOptionInterface
{
    private ?string $label = null;
    private ?int $optionId = null;

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getOptionId(): ?int
    {
        return $this->optionId;
    }

    public function setLabel(?string $value): PersonalizationQuestionOptionInterface
    {
        $this->label = $value;

        return $this;
    }

    public function setOptionId(?int $value): PersonalizationQuestionOptionInterface
    {
        $this->optionId = $value;

        return $this;
    }
}
