<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * One selectable option of a personalization question.
 */
interface PersonalizationQuestionOptionRequestInterface
{
    public function getLabel(): string;

    public function getOptionId(): ?int;

    public function setLabel(string $value): self;

    public function setOptionId(?int $value): self;
}
