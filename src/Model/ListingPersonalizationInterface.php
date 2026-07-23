<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingPersonalizationInterface
{
    /**
     * @return array<int, PersonalizationQuestionInterface>
     */
    public function getPersonalizationQuestions(): array;

    /**
     * @param array<int, PersonalizationQuestionInterface> $value
     */
    public function setPersonalizationQuestions(array $value): self;
}
