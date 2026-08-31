<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionRequestInterface;
use ChristianBrown\Etsy\Model\PersonalizationQuestionRequest;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionOptionRequestsSerializerInterface;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionRequestSerializer;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PersonalizationQuestionRequest::class)]
#[CoversClass(PersonalizationQuestionRequestSerializer::class)]
final class PersonalizationQuestionRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $questionText = 'test-questionText';
        $questionType = 'test-questionType';
        $required = true;
        $addOnPrice = 1.5;
        $instructions = 'test-instructions';
        $maxAllowedCharacters = 2;
        $maxAllowedFiles = 3;
        $options = [self::createStub(PersonalizationQuestionOptionRequestInterface::class)];
        $questionId = 4;

        $personalizationQuestionOptionRequestsSerializer = self::createStub(PersonalizationQuestionOptionRequestsSerializerInterface::class);
        $personalizationQuestionOptionRequestsSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$options, [['test-options']]],
                ]
            );

        $personalizationQuestionRequest = (new PersonalizationQuestionRequest($questionText, $questionType, $required))
            ->setQuestionText($questionText)
            ->setQuestionType($questionType)
            ->setRequired($required)
            ->setAddOnPrice($addOnPrice)
            ->setInstructions($instructions)
            ->setMaxAllowedCharacters($maxAllowedCharacters)
            ->setMaxAllowedFiles($maxAllowedFiles)
            ->setOptions($options)
            ->setQuestionId($questionId);

        $serializer = new PersonalizationQuestionRequestSerializer($personalizationQuestionOptionRequestsSerializer);

        $expected = [
            PersonalizationQuestionRequestSerializerInterface::KEY_ADD_ON_PRICE => $addOnPrice,
            PersonalizationQuestionRequestSerializerInterface::KEY_INSTRUCTIONS => $instructions,
            PersonalizationQuestionRequestSerializerInterface::KEY_MAX_ALLOWED_CHARACTERS => $maxAllowedCharacters,
            PersonalizationQuestionRequestSerializerInterface::KEY_MAX_ALLOWED_FILES => $maxAllowedFiles,
            PersonalizationQuestionRequestSerializerInterface::KEY_OPTIONS => [['test-options']],
            PersonalizationQuestionRequestSerializerInterface::KEY_QUESTION_ID => $questionId,
            PersonalizationQuestionRequestSerializerInterface::KEY_QUESTION_TEXT => $questionText,
            PersonalizationQuestionRequestSerializerInterface::KEY_QUESTION_TYPE => $questionType,
            PersonalizationQuestionRequestSerializerInterface::KEY_REQUIRED => $required,
        ];

        self::assertSame($expected, $serializer->serialize($personalizationQuestionRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $questionText = 'test-questionText';
        $questionType = 'test-questionType';
        $required = true;

        $personalizationQuestionOptionRequestsSerializer = self::createStub(PersonalizationQuestionOptionRequestsSerializerInterface::class);

        $personalizationQuestionRequest = new PersonalizationQuestionRequest($questionText, $questionType, $required);

        $serializer = new PersonalizationQuestionRequestSerializer($personalizationQuestionOptionRequestsSerializer);

        $expected = [
            PersonalizationQuestionRequestSerializerInterface::KEY_QUESTION_TEXT => $questionText,
            PersonalizationQuestionRequestSerializerInterface::KEY_QUESTION_TYPE => $questionType,
            PersonalizationQuestionRequestSerializerInterface::KEY_REQUIRED => $required,
        ];

        self::assertSame($expected, $serializer->serialize($personalizationQuestionRequest));
    }
}
