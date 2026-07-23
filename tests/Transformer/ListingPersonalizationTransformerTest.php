<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\ListingPersonalization;
use ChristianBrown\Etsy\Model\PersonalizationQuestionInterface;
use ChristianBrown\Etsy\Transformer\ListingPersonalizationTransformer;
use ChristianBrown\Etsy\Transformer\ListingPersonalizationTransformerInterface;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionsTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingPersonalization::class)]
#[CoversClass(ListingPersonalizationTransformer::class)]
final class ListingPersonalizationTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $questionsData = [['__question__']];
        $question = self::createStub(PersonalizationQuestionInterface::class);

        $questionsTransformer = self::createMock(PersonalizationQuestionsTransformerInterface::class);
        $questionsTransformer->expects(self::once())->method('transform')
            ->with($questionsData)
            ->willReturn([$question]);

        $transformer = new ListingPersonalizationTransformer($questionsTransformer);

        $actual = $transformer->transform([
            ListingPersonalizationTransformerInterface::KEY_PERSONALIZATION_QUESTIONS => $questionsData,
        ]);

        self::assertSame([$question], $actual->getPersonalizationQuestions());
    }

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('provideTransformQuestionsNotSetCases')]
    public function testTransformQuestionsNotSet(array $data): void
    {
        $questionsTransformer = self::createMock(PersonalizationQuestionsTransformerInterface::class);
        $questionsTransformer->expects(self::never())->method('transform');

        $transformer = new ListingPersonalizationTransformer($questionsTransformer);

        self::assertSame([], $transformer->transform($data)->getPersonalizationQuestions());
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function provideTransformQuestionsNotSetCases(): iterable
    {
        yield 'absent' => [[]];
        yield 'nonArray' => [[ListingPersonalizationTransformerInterface::KEY_PERSONALIZATION_QUESTIONS => 'not-an-array']];
    }
}
