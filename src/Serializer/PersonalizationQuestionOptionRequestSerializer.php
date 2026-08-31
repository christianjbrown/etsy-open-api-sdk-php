<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionRequestInterface;

final class PersonalizationQuestionOptionRequestSerializer implements PersonalizationQuestionOptionRequestSerializerInterface
{
    /**
     * @return array<string, mixed>
     */
    public function serialize(PersonalizationQuestionOptionRequestInterface $personalizationQuestionOptionRequest): array
    {
        $data = [];

        $data = self::applyLabel($data, $personalizationQuestionOptionRequest);
        $data = self::applyOptionId($data, $personalizationQuestionOptionRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyLabel(array $data, PersonalizationQuestionOptionRequestInterface $personalizationQuestionOptionRequest): array
    {
        $data[self::KEY_LABEL] = $personalizationQuestionOptionRequest->getLabel();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyOptionId(array $data, PersonalizationQuestionOptionRequestInterface $personalizationQuestionOptionRequest): array
    {
        $value = $personalizationQuestionOptionRequest->getOptionId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_OPTION_ID] = $value;

        return $data;
    }
}
