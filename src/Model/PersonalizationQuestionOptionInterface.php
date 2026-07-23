<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface PersonalizationQuestionOptionInterface
{
    public function getLabel(): ?string;

    public function getOptionId(): ?int;

    public function setLabel(?string $value): self;

    public function setOptionId(?int $value): self;
}
