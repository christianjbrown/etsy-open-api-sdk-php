<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionRequest;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionOptionRequestSerializer;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionOptionRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PersonalizationQuestionOptionRequest::class)]
#[CoversClass(PersonalizationQuestionOptionRequestSerializer::class)]
final class PersonalizationQuestionOptionRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $label = 'test-label';
        $optionId = 1;

        $personalizationQuestionOptionRequest = (new PersonalizationQuestionOptionRequest($label))
            ->setLabel($label)
            ->setOptionId($optionId);

        $serializer = new PersonalizationQuestionOptionRequestSerializer();

        $expected = [
            PersonalizationQuestionOptionRequestSerializerInterface::KEY_LABEL => $label,
            PersonalizationQuestionOptionRequestSerializerInterface::KEY_OPTION_ID => $optionId,
        ];

        self::assertSame($expected, $serializer->serialize($personalizationQuestionOptionRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $label = 'test-label';

        $personalizationQuestionOptionRequest = new PersonalizationQuestionOptionRequest($label);

        $serializer = new PersonalizationQuestionOptionRequestSerializer();

        $expected = [
            PersonalizationQuestionOptionRequestSerializerInterface::KEY_LABEL => $label,
        ];

        self::assertSame($expected, $serializer->serialize($personalizationQuestionOptionRequest));
    }
}
