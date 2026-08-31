<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionRequestInterface;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\PersonalizationQuestionRequestsSerializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PersonalizationQuestionRequestsSerializer::class)]
final class PersonalizationQuestionRequestsSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $first = self::createStub(PersonalizationQuestionRequestInterface::class);
        $second = self::createStub(PersonalizationQuestionRequestInterface::class);

        $personalizationQuestionRequestSerializer = self::createStub(PersonalizationQuestionRequestSerializerInterface::class);
        $personalizationQuestionRequestSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$first, ['first' => 'test-1']],
                    [$second, ['second' => 'test-2']],
                ]
            );

        $serializer = new PersonalizationQuestionRequestsSerializer($personalizationQuestionRequestSerializer);

        self::assertSame([['first' => 'test-1'], ['second' => 'test-2']], $serializer->serialize([$first, $second]));
    }

    public function testSerializeEmpty(): void
    {
        $serializer = new PersonalizationQuestionRequestsSerializer(self::createStub(PersonalizationQuestionRequestSerializerInterface::class));

        self::assertSame([], $serializer->serialize([]));
    }
}
