<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingPersonalization;
use ChristianBrown\Etsy\Model\ListingPersonalizationInterface;

use function is_array;

final class ListingPersonalizationTransformer implements ListingPersonalizationTransformerInterface
{
    private PersonalizationQuestionsTransformerInterface $personalizationQuestionsTransformer;

    public function __construct(PersonalizationQuestionsTransformerInterface $personalizationQuestionsTransformer)
    {
        $this->personalizationQuestionsTransformer = $personalizationQuestionsTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingPersonalizationInterface
    {
        $personalization = new ListingPersonalization();

        $this->applyPersonalizationQuestions($personalization, $data);

        return $personalization;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPersonalizationQuestions(ListingPersonalization $personalization, array $data): void
    {
        if (empty($data[self::KEY_PERSONALIZATION_QUESTIONS])) {
            return;
        }
        if (!is_array($data[self::KEY_PERSONALIZATION_QUESTIONS])) {
            return;
        }
        $personalization->setPersonalizationQuestions($this->personalizationQuestionsTransformer->transform($data[self::KEY_PERSONALIZATION_QUESTIONS]));
    }
}
