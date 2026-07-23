<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOption;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionTransformer;
use ChristianBrown\Etsy\Transformer\PersonalizationQuestionOptionTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(PersonalizationQuestionOption::class)]
#[CoversClass(PersonalizationQuestionOptionTransformer::class)]
final class PersonalizationQuestionOptionTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            PersonalizationQuestionOptionTransformerInterface::KEY_LABEL => 'v_label',
            PersonalizationQuestionOptionTransformerInterface::KEY_OPTION_ID => 100,
        ];

        $transformer = new PersonalizationQuestionOptionTransformer();

        $actual = $transformer->transform($data);

        self::assertSame('v_label', $actual->getLabel());
        self::assertSame(100, $actual->getOptionId());
    }

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, ?string $expectedLabel, ?int $expectedOptionId): void
    {
        $transformer = new PersonalizationQuestionOptionTransformer();

        $actual = $transformer->transform($data);

        self::assertSame($expectedLabel, $actual->getLabel());
        self::assertSame($expectedOptionId, $actual->getOptionId());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, ?string, ?int}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $label = PersonalizationQuestionOptionTransformerInterface::KEY_LABEL;
        $optionId = PersonalizationQuestionOptionTransformerInterface::KEY_OPTION_ID;

        yield 'allAbsent' => [[], null, null];
        yield 'labelWrongType' => [[$label => 42], null, null];
        yield 'optionIdZero' => [[$optionId => 0], null, 0];
        yield 'optionIdWrongType' => [[$optionId => 'not-int'], null, null];
    }
}
