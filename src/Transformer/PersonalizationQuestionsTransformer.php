<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PersonalizationQuestionInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class PersonalizationQuestionsTransformer implements PersonalizationQuestionsTransformerInterface
{
    private PersonalizationQuestionTransformerInterface $personalizationQuestionTransformer;

    public function __construct(PersonalizationQuestionTransformerInterface $personalizationQuestionTransformer)
    {
        $this->personalizationQuestionTransformer = $personalizationQuestionTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, PersonalizationQuestionInterface>
     */
    public function transform(array $data): array
    {
        $questions = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $questionData = $values[$i];
            if (!is_array($questionData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $questions[] = $this->personalizationQuestionTransformer->transform($questionData);
        }

        return $questions;
    }
}
