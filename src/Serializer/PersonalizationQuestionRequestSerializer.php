<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionRequestInterface;

final class PersonalizationQuestionRequestSerializer implements PersonalizationQuestionRequestSerializerInterface
{
    private PersonalizationQuestionOptionRequestsSerializerInterface $personalizationQuestionOptionRequestsSerializer;

    public function __construct(PersonalizationQuestionOptionRequestsSerializerInterface $personalizationQuestionOptionRequestsSerializer)
    {
        $this->personalizationQuestionOptionRequestsSerializer = $personalizationQuestionOptionRequestsSerializer;
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array
    {
        $data = [];

        $data = self::applyAddOnPrice($data, $personalizationQuestionRequest);
        $data = self::applyInstructions($data, $personalizationQuestionRequest);
        $data = self::applyMaxAllowedCharacters($data, $personalizationQuestionRequest);
        $data = self::applyMaxAllowedFiles($data, $personalizationQuestionRequest);
        $data = $this->applyOptions($data, $personalizationQuestionRequest);
        $data = self::applyQuestionId($data, $personalizationQuestionRequest);
        $data = self::applyQuestionText($data, $personalizationQuestionRequest);
        $data = self::applyQuestionType($data, $personalizationQuestionRequest);
        $data = self::applyRequired($data, $personalizationQuestionRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyAddOnPrice(array $data, PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array
    {
        $value = $personalizationQuestionRequest->getAddOnPrice();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ADD_ON_PRICE] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyInstructions(array $data, PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array
    {
        $value = $personalizationQuestionRequest->getInstructions();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_INSTRUCTIONS] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyMaxAllowedCharacters(array $data, PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array
    {
        $value = $personalizationQuestionRequest->getMaxAllowedCharacters();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_MAX_ALLOWED_CHARACTERS] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyMaxAllowedFiles(array $data, PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array
    {
        $value = $personalizationQuestionRequest->getMaxAllowedFiles();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_MAX_ALLOWED_FILES] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function applyOptions(array $data, PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array
    {
        $value = $personalizationQuestionRequest->getOptions();
        if (empty($value)) {
            return $data;
        }
        $data[self::KEY_OPTIONS] = $this->personalizationQuestionOptionRequestsSerializer->serialize($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyQuestionId(array $data, PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array
    {
        $value = $personalizationQuestionRequest->getQuestionId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_QUESTION_ID] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyQuestionText(array $data, PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array
    {
        $data[self::KEY_QUESTION_TEXT] = $personalizationQuestionRequest->getQuestionText();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyQuestionType(array $data, PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array
    {
        $data[self::KEY_QUESTION_TYPE] = $personalizationQuestionRequest->getQuestionType();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyRequired(array $data, PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array
    {
        $data[self::KEY_REQUIRED] = $personalizationQuestionRequest->getRequired();

        return $data;
    }
}
