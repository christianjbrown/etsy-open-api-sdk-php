<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\TransactionVariation;
use ChristianBrown\Etsy\Transformer\TransactionVariationTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(TransactionVariation::class)]
#[CoversClass(TransactionVariationTransformer::class)]
final class TransactionVariationTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            TransactionVariationTransformerInterface::KEY_FORMATTED_NAME => 'Color',
            TransactionVariationTransformerInterface::KEY_FORMATTED_VALUE => 'Blue',
            TransactionVariationTransformerInterface::KEY_PROPERTY_ID => 200,
            TransactionVariationTransformerInterface::KEY_QUESTION_ID => 300,
            TransactionVariationTransformerInterface::KEY_VALUE_ID => 400,
        ];

        $transformer = new TransactionVariationTransformer();

        $actual = $transformer->transform($data);

        self::assertSame('Color', $actual->getFormattedName());
        self::assertSame('Blue', $actual->getFormattedValue());
        self::assertSame(200, $actual->getPropertyId());
        self::assertSame(300, $actual->getQuestionId());
        self::assertSame(400, $actual->getValueId());
    }

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, ?string $expectedFormattedName, ?string $expectedFormattedValue, ?int $expectedPropertyId, ?int $expectedQuestionId, ?int $expectedValueId): void
    {
        $transformer = new TransactionVariationTransformer();

        $actual = $transformer->transform($data);

        self::assertSame($expectedFormattedName, $actual->getFormattedName());
        self::assertSame($expectedFormattedValue, $actual->getFormattedValue());
        self::assertSame($expectedPropertyId, $actual->getPropertyId());
        self::assertSame($expectedQuestionId, $actual->getQuestionId());
        self::assertSame($expectedValueId, $actual->getValueId());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, ?string, ?string, ?int, ?int, ?int}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $formattedName = TransactionVariationTransformerInterface::KEY_FORMATTED_NAME;
        $formattedValue = TransactionVariationTransformerInterface::KEY_FORMATTED_VALUE;
        $propertyId = TransactionVariationTransformerInterface::KEY_PROPERTY_ID;
        $questionId = TransactionVariationTransformerInterface::KEY_QUESTION_ID;
        $valueId = TransactionVariationTransformerInterface::KEY_VALUE_ID;

        yield 'allAbsent' => [[], null, null, null, null, null];
        yield 'formattedNameWrongType' => [[$formattedName => 42], null, null, null, null, null];
        yield 'formattedValueWrongType' => [[$formattedValue => 42], null, null, null, null, null];
        yield 'propertyIdZero' => [[$propertyId => 0], null, null, 0, null, null];
        yield 'propertyIdWrongType' => [[$propertyId => 'not-int'], null, null, null, null, null];
        yield 'questionIdZero' => [[$questionId => 0], null, null, null, 0, null];
        yield 'questionIdWrongType' => [[$questionId => 'not-int'], null, null, null, null, null];
        yield 'valueIdZero' => [[$valueId => 0], null, null, null, null, 0];
        yield 'valueIdWrongType' => [[$valueId => 'not-int'], null, null, null, null, null];
    }
}
