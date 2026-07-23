<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingPersonalization implements ListingPersonalizationInterface
{
    /**
     * @var array<int, PersonalizationQuestionInterface>
     */
    private array $personalizationQuestions = [];

    /**
     * @return array<int, PersonalizationQuestionInterface>
     */
    public function getPersonalizationQuestions(): array
    {
        return $this->personalizationQuestions;
    }

    /**
     * @param array<int, PersonalizationQuestionInterface> $value
     */
    public function setPersonalizationQuestions(array $value): ListingPersonalizationInterface
    {
        $this->personalizationQuestions = $value;

        return $this;
    }
}
