<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionRequestInterface;
use ChristianBrown\Etsy\Model\UpdateListingPersonalizationRequest;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionRequestsSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingPersonalizationRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateListingPersonalizationRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateListingPersonalizationRequest::class)]
#[CoversClass(UpdateListingPersonalizationRequestSerializer::class)]
final class UpdateListingPersonalizationRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $personalizationQuestions = [self::createStub(PersonalizationQuestionRequestInterface::class)];

        $personalizationQuestionRequestsSerializer = self::createStub(PersonalizationQuestionRequestsSerializerInterface::class);
        $personalizationQuestionRequestsSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$personalizationQuestions, [['test-personalizationQuestions']]],
                ]
            );

        $updateListingPersonalizationRequest = (new UpdateListingPersonalizationRequest($personalizationQuestions))
            ->setPersonalizationQuestions($personalizationQuestions);

        $serializer = new UpdateListingPersonalizationRequestSerializer($personalizationQuestionRequestsSerializer);

        $expected = [
            UpdateListingPersonalizationRequestSerializerInterface::KEY_PERSONALIZATION_QUESTIONS => [['test-personalizationQuestions']],
        ];

        self::assertSame($expected, $serializer->serialize($updateListingPersonalizationRequest));
    }
}
