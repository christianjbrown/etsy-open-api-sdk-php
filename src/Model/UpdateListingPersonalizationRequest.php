<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateListingPersonalizationRequest implements UpdateListingPersonalizationRequestInterface
{
    /**
     * @var array<int, PersonalizationQuestionRequestInterface>
     */
    private array $personalizationQuestions = [];

    /**
     * @param array<int, PersonalizationQuestionRequestInterface> $personalizationQuestions
     */
    public function __construct(array $personalizationQuestions)
    {
        $this->personalizationQuestions = $personalizationQuestions;
    }

    /**
     * @return array<int, PersonalizationQuestionRequestInterface>
     */
    public function getPersonalizationQuestions(): array
    {
        return $this->personalizationQuestions;
    }

    /**
     * @param array<int, PersonalizationQuestionRequestInterface> $value
     */
    public function setPersonalizationQuestions(array $value): UpdateListingPersonalizationRequestInterface
    {
        $this->personalizationQuestions = $value;

        return $this;
    }
}
