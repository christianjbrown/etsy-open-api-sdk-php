<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class PersonalizationQuestionOptionsTransformer implements PersonalizationQuestionOptionsTransformerInterface
{
    private PersonalizationQuestionOptionTransformerInterface $personalizationQuestionOptionTransformer;

    public function __construct(PersonalizationQuestionOptionTransformerInterface $personalizationQuestionOptionTransformer)
    {
        $this->personalizationQuestionOptionTransformer = $personalizationQuestionOptionTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, PersonalizationQuestionOptionInterface>
     */
    public function transform(array $data): array
    {
        $options = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $optionData = $values[$i];
            if (!is_array($optionData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $options[] = $this->personalizationQuestionOptionTransformer->transform($optionData);
        }

        return $options;
    }
}
