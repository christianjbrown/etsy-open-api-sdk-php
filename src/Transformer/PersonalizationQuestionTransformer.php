<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PersonalizationQuestion;
use ChristianBrown\Etsy\Model\PersonalizationQuestionInterface;

use function is_array;
use function is_bool;
use function is_int;
use function is_string;

final class PersonalizationQuestionTransformer implements PersonalizationQuestionTransformerInterface
{
    private MoneyTransformerInterface $moneyTransformer;
    private PersonalizationQuestionOptionsTransformerInterface $personalizationQuestionOptionsTransformer;

    public function __construct(MoneyTransformerInterface $moneyTransformer, PersonalizationQuestionOptionsTransformerInterface $personalizationQuestionOptionsTransformer)
    {
        $this->moneyTransformer = $moneyTransformer;
        $this->personalizationQuestionOptionsTransformer = $personalizationQuestionOptionsTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PersonalizationQuestionInterface
    {
        $question = new PersonalizationQuestion();

        $this->applyAddOnPrice($question, $data);
        self::applyInstructions($question, $data);
        self::applyMaxAllowedCharacters($question, $data);
        self::applyMaxAllowedFiles($question, $data);
        $this->applyOptions($question, $data);
        self::applyQuestionId($question, $data);
        self::applyQuestionText($question, $data);
        self::applyQuestionType($question, $data);
        self::applyRequired($question, $data);

        return $question;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyAddOnPrice(PersonalizationQuestion $question, array $data): void
    {
        if (empty($data[self::KEY_ADD_ON_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_ADD_ON_PRICE])) {
            return;
        }
        $question->setAddOnPrice($this->moneyTransformer->transform($data[self::KEY_ADD_ON_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyInstructions(PersonalizationQuestion $question, array $data): void
    {
        if (empty($data[self::KEY_INSTRUCTIONS])) {
            return;
        }
        if (!is_string($data[self::KEY_INSTRUCTIONS])) {
            return;
        }
        $question->setInstructions($data[self::KEY_INSTRUCTIONS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMaxAllowedCharacters(PersonalizationQuestion $question, array $data): void
    {
        if (!isset($data[self::KEY_MAX_ALLOWED_CHARACTERS])) {
            return;
        }
        if (!is_int($data[self::KEY_MAX_ALLOWED_CHARACTERS])) {
            return;
        }
        $question->setMaxAllowedCharacters($data[self::KEY_MAX_ALLOWED_CHARACTERS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMaxAllowedFiles(PersonalizationQuestion $question, array $data): void
    {
        if (!isset($data[self::KEY_MAX_ALLOWED_FILES])) {
            return;
        }
        if (!is_int($data[self::KEY_MAX_ALLOWED_FILES])) {
            return;
        }
        $question->setMaxAllowedFiles($data[self::KEY_MAX_ALLOWED_FILES]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyOptions(PersonalizationQuestion $question, array $data): void
    {
        if (empty($data[self::KEY_OPTIONS])) {
            return;
        }
        if (!is_array($data[self::KEY_OPTIONS])) {
            return;
        }
        $question->setOptions($this->personalizationQuestionOptionsTransformer->transform($data[self::KEY_OPTIONS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyQuestionId(PersonalizationQuestion $question, array $data): void
    {
        if (!isset($data[self::KEY_QUESTION_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_QUESTION_ID])) {
            return;
        }
        $question->setQuestionId($data[self::KEY_QUESTION_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyQuestionText(PersonalizationQuestion $question, array $data): void
    {
        if (empty($data[self::KEY_QUESTION_TEXT])) {
            return;
        }
        if (!is_string($data[self::KEY_QUESTION_TEXT])) {
            return;
        }
        $question->setQuestionText($data[self::KEY_QUESTION_TEXT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyQuestionType(PersonalizationQuestion $question, array $data): void
    {
        if (empty($data[self::KEY_QUESTION_TYPE])) {
            return;
        }
        if (!is_string($data[self::KEY_QUESTION_TYPE])) {
            return;
        }
        $question->setQuestionType($data[self::KEY_QUESTION_TYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyRequired(PersonalizationQuestion $question, array $data): void
    {
        if (!isset($data[self::KEY_REQUIRED])) {
            return;
        }
        if (!is_bool($data[self::KEY_REQUIRED])) {
            return;
        }
        $question->setRequired($data[self::KEY_REQUIRED]);
    }
}
