<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Model\PersonalizationQuestion;
use ChristianBrown\Etsy\Model\PersonalizationQuestionInterface;
use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionInterface;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(PersonalizationQuestion::class)]
#[CoversClass(PersonalizationQuestionTransformer::class)]
final class PersonalizationQuestionTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $addOnPriceData = ['__addOnPrice__'];
        $addOnPrice = self::createStub(MoneyInterface::class);
        $optionsData = [['__option__']];
        $option = self::createStub(PersonalizationQuestionOptionInterface::class);

        $data = [
            PersonalizationQuestionTransformerInterface::KEY_ADD_ON_PRICE => $addOnPriceData,
            PersonalizationQuestionTransformerInterface::KEY_INSTRUCTIONS => 'v_instructions',
            PersonalizationQuestionTransformerInterface::KEY_MAX_ALLOWED_CHARACTERS => 101,
            PersonalizationQuestionTransformerInterface::KEY_MAX_ALLOWED_FILES => 102,
            PersonalizationQuestionTransformerInterface::KEY_OPTIONS => $optionsData,
            PersonalizationQuestionTransformerInterface::KEY_QUESTION_ID => 103,
            PersonalizationQuestionTransformerInterface::KEY_QUESTION_TEXT => 'v_questionText',
            PersonalizationQuestionTransformerInterface::KEY_QUESTION_TYPE => 'v_questionType',
            PersonalizationQuestionTransformerInterface::KEY_REQUIRED => true,
        ];

        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);
        $moneyTransformer->method('transform')->willReturn($addOnPrice);

        $optionsTransformer = self::createStub(PersonalizationQuestionOptionsTransformerInterface::class);
        $optionsTransformer->method('transform')->willReturn([$option]);

        $transformer = new PersonalizationQuestionTransformer($moneyTransformer, $optionsTransformer);

        $actual = $transformer->transform($data);

        self::assertSame($addOnPrice, $actual->getAddOnPrice());
        self::assertSame('v_instructions', $actual->getInstructions());
        self::assertSame(101, $actual->getMaxAllowedCharacters());
        self::assertSame(102, $actual->getMaxAllowedFiles());
        self::assertSame([$option], $actual->getOptions());
        self::assertSame(103, $actual->getQuestionId());
        self::assertSame('v_questionText', $actual->getQuestionText());
        self::assertSame('v_questionType', $actual->getQuestionType());
        self::assertTrue($actual->getRequired());
    }

    /**
     * @param array<string, mixed>                            $data
     * @param Closure(PersonalizationQuestionInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);
        $optionsTransformer = self::createStub(PersonalizationQuestionOptionsTransformerInterface::class);

        $transformer = new PersonalizationQuestionTransformer($moneyTransformer, $optionsTransformer);

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(PersonalizationQuestionInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        yield 'allAbsent' => [
            [],
            static function (PersonalizationQuestionInterface $m): void {
                self::assertNull($m->getAddOnPrice());
                self::assertNull($m->getInstructions());
                self::assertNull($m->getMaxAllowedCharacters());
                self::assertNull($m->getMaxAllowedFiles());
                self::assertSame([], $m->getOptions());
                self::assertNull($m->getQuestionId());
                self::assertNull($m->getQuestionText());
                self::assertNull($m->getQuestionType());
                self::assertNull($m->getRequired());
            },
        ];

        yield 'addOnPriceNonArray' => [[PersonalizationQuestionTransformerInterface::KEY_ADD_ON_PRICE => 'x'], static function (PersonalizationQuestionInterface $m): void {
            self::assertNull($m->getAddOnPrice());
        }];
        yield 'instructionsWrongType' => [[PersonalizationQuestionTransformerInterface::KEY_INSTRUCTIONS => 42], static function (PersonalizationQuestionInterface $m): void {
            self::assertNull($m->getInstructions());
        }];
        yield 'maxAllowedCharactersWrongType' => [[PersonalizationQuestionTransformerInterface::KEY_MAX_ALLOWED_CHARACTERS => 'x'], static function (PersonalizationQuestionInterface $m): void {
            self::assertNull($m->getMaxAllowedCharacters());
        }];
        yield 'maxAllowedFilesWrongType' => [[PersonalizationQuestionTransformerInterface::KEY_MAX_ALLOWED_FILES => 'x'], static function (PersonalizationQuestionInterface $m): void {
            self::assertNull($m->getMaxAllowedFiles());
        }];
        yield 'optionsNonArray' => [[PersonalizationQuestionTransformerInterface::KEY_OPTIONS => 'x'], static function (PersonalizationQuestionInterface $m): void {
            self::assertSame([], $m->getOptions());
        }];
        yield 'questionIdWrongType' => [[PersonalizationQuestionTransformerInterface::KEY_QUESTION_ID => 'x'], static function (PersonalizationQuestionInterface $m): void {
            self::assertNull($m->getQuestionId());
        }];
        yield 'questionTextWrongType' => [[PersonalizationQuestionTransformerInterface::KEY_QUESTION_TEXT => 42], static function (PersonalizationQuestionInterface $m): void {
            self::assertNull($m->getQuestionText());
        }];
        yield 'questionTypeWrongType' => [[PersonalizationQuestionTransformerInterface::KEY_QUESTION_TYPE => 42], static function (PersonalizationQuestionInterface $m): void {
            self::assertNull($m->getQuestionType());
        }];
        yield 'requiredWrongType' => [[PersonalizationQuestionTransformerInterface::KEY_REQUIRED => 'x'], static function (PersonalizationQuestionInterface $m): void {
            self::assertNull($m->getRequired());
        }];
    }

    public function testTransformTransformsNestedResources(): void
    {
        $addOnPriceData = ['__addOnPrice__'];
        $addOnPrice = self::createStub(MoneyInterface::class);
        $optionsData = [['__option__']];
        $option = self::createStub(PersonalizationQuestionOptionInterface::class);

        $moneyTransformer = self::createMock(MoneyTransformerInterface::class);
        $moneyTransformer->expects(self::once())->method('transform')
            ->with($addOnPriceData)
            ->willReturn($addOnPrice);

        $optionsTransformer = self::createMock(PersonalizationQuestionOptionsTransformerInterface::class);
        $optionsTransformer->expects(self::once())->method('transform')
            ->with($optionsData)
            ->willReturn([$option]);

        $transformer = new PersonalizationQuestionTransformer($moneyTransformer, $optionsTransformer);

        $actual = $transformer->transform([
            PersonalizationQuestionTransformerInterface::KEY_ADD_ON_PRICE => $addOnPriceData,
            PersonalizationQuestionTransformerInterface::KEY_OPTIONS => $optionsData,
        ]);

        self::assertSame($addOnPrice, $actual->getAddOnPrice());
        self::assertSame([$option], $actual->getOptions());
    }
}
