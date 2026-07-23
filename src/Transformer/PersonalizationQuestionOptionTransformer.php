<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOption;
use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionInterface;

use function is_int;
use function is_string;

final class PersonalizationQuestionOptionTransformer implements PersonalizationQuestionOptionTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PersonalizationQuestionOptionInterface
    {
        $option = new PersonalizationQuestionOption();

        self::applyLabel($option, $data);
        self::applyOptionId($option, $data);

        return $option;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLabel(PersonalizationQuestionOption $option, array $data): void
    {
        if (empty($data[self::KEY_LABEL])) {
            return;
        }
        if (!is_string($data[self::KEY_LABEL])) {
            return;
        }
        $option->setLabel($data[self::KEY_LABEL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyOptionId(PersonalizationQuestionOption $option, array $data): void
    {
        if (!isset($data[self::KEY_OPTION_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_OPTION_ID])) {
            return;
        }
        $option->setOptionId($data[self::KEY_OPTION_ID]);
    }
}
