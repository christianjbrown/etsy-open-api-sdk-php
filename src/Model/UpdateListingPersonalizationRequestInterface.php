<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `updateListingPersonalization` call.
 */
interface UpdateListingPersonalizationRequestInterface
{
    /**
     * @return array<int, PersonalizationQuestionRequestInterface>
     */
    public function getPersonalizationQuestions(): array;

    /**
     * @param array<int, PersonalizationQuestionRequestInterface> $value
     */
    public function setPersonalizationQuestions(array $value): self;
}
