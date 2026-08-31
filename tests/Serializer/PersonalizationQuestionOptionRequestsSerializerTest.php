<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionRequestInterface;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionOptionRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionOptionRequestsSerializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PersonalizationQuestionOptionRequestsSerializer::class)]
final class PersonalizationQuestionOptionRequestsSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $first = self::createStub(PersonalizationQuestionOptionRequestInterface::class);
        $second = self::createStub(PersonalizationQuestionOptionRequestInterface::class);

        $personalizationQuestionOptionRequestSerializer = self::createStub(PersonalizationQuestionOptionRequestSerializerInterface::class);
        $personalizationQuestionOptionRequestSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$first, ['first' => 'test-1']],
                    [$second, ['second' => 'test-2']],
                ]
            );

        $serializer = new PersonalizationQuestionOptionRequestsSerializer($personalizationQuestionOptionRequestSerializer);

        self::assertSame([['first' => 'test-1'], ['second' => 'test-2']], $serializer->serialize([$first, $second]));
    }

    public function testSerializeEmpty(): void
    {
        $serializer = new PersonalizationQuestionOptionRequestsSerializer(self::createStub(PersonalizationQuestionOptionRequestSerializerInterface::class));

        self::assertSame([], $serializer->serialize([]));
    }
}
