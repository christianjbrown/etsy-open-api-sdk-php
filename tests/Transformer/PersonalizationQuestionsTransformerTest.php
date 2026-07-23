<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PersonalizationQuestionInterface;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionsTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(PersonalizationQuestionsTransformer::class)]
final class PersonalizationQuestionsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-question-1'], ['test-question-2']];

        $question1 = self::createStub(PersonalizationQuestionInterface::class);
        $question2 = self::createStub(PersonalizationQuestionInterface::class);

        $questionTransformer = self::createStub(PersonalizationQuestionTransformerInterface::class);
        $questionTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-question-1'], $question1],
                    [['test-question-2'], $question2],
                ]
            );

        $transformer = new PersonalizationQuestionsTransformer($questionTransformer);

        self::assertSame([$question1, $question2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $questionTransformer = self::createStub(PersonalizationQuestionTransformerInterface::class);

        $transformer = new PersonalizationQuestionsTransformer($questionTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $questionTransformer = self::createStub(PersonalizationQuestionTransformerInterface::class);

        $transformer = new PersonalizationQuestionsTransformer($questionTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PersonalizationQuestionsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, PersonalizationQuestionsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
