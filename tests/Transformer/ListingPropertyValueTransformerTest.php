<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\ListingPropertyValue;
use ChristianBrown\Etsy\Transformer\ListingPropertyValueTransformer;
use ChristianBrown\Etsy\Transformer\ListingPropertyValueTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingPropertyValue::class)]
#[CoversClass(ListingPropertyValueTransformer::class)]
final class ListingPropertyValueTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            ListingPropertyValueTransformerInterface::KEY_PROPERTY_ID => 500,
            ListingPropertyValueTransformerInterface::KEY_PROPERTY_NAME => 'Size',
            ListingPropertyValueTransformerInterface::KEY_SCALE_ID => 600,
            ListingPropertyValueTransformerInterface::KEY_SCALE_NAME => 'inches',
            ListingPropertyValueTransformerInterface::KEY_VALUE_IDS => [1, 'skip-me', 2],
            ListingPropertyValueTransformerInterface::KEY_VALUES => ['small', 42, 'large'],
        ];

        $transformer = new ListingPropertyValueTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(500, $actual->getPropertyId());
        self::assertSame('Size', $actual->getPropertyName());
        self::assertSame(600, $actual->getScaleId());
        self::assertSame('inches', $actual->getScaleName());
        self::assertSame([1, 2], $actual->getValueIds());
        self::assertSame(['small', 'large'], $actual->getValues());
    }

    /**
     * @param array<string, mixed> $data
     * @param array<int, int>      $expectedValueIds
     * @param array<int, string>   $expectedValues
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, array $expectedValueIds, array $expectedValues, ?int $expectedPropertyId, ?string $expectedPropertyName, ?int $expectedScaleId, ?string $expectedScaleName): void
    {
        $transformer = new ListingPropertyValueTransformer();

        $actual = $transformer->transform($data);

        self::assertSame($expectedValueIds, $actual->getValueIds());
        self::assertSame($expectedValues, $actual->getValues());
        self::assertSame($expectedPropertyId, $actual->getPropertyId());
        self::assertSame($expectedPropertyName, $actual->getPropertyName());
        self::assertSame($expectedScaleId, $actual->getScaleId());
        self::assertSame($expectedScaleName, $actual->getScaleName());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, array<int, int>, array<int, string>, ?int, ?string, ?int, ?string}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $propertyId = ListingPropertyValueTransformerInterface::KEY_PROPERTY_ID;
        $propertyName = ListingPropertyValueTransformerInterface::KEY_PROPERTY_NAME;
        $scaleId = ListingPropertyValueTransformerInterface::KEY_SCALE_ID;
        $scaleName = ListingPropertyValueTransformerInterface::KEY_SCALE_NAME;
        $valueIds = ListingPropertyValueTransformerInterface::KEY_VALUE_IDS;
        $values = ListingPropertyValueTransformerInterface::KEY_VALUES;

        yield 'allAbsent' => [[], [], [], null, null, null, null];
        yield 'propertyIdZero' => [[$propertyId => 0], [], [], 0, null, null, null];
        yield 'propertyIdWrongType' => [[$propertyId => 'not-int'], [], [], null, null, null, null];
        yield 'propertyNameWrongType' => [[$propertyName => 42], [], [], null, null, null, null];
        yield 'scaleIdZero' => [[$scaleId => 0], [], [], null, null, 0, null];
        yield 'scaleIdWrongType' => [[$scaleId => 'not-int'], [], [], null, null, null, null];
        yield 'scaleNameWrongType' => [[$scaleName => 42], [], [], null, null, null, null];
        yield 'valueIdsWrongType' => [[$valueIds => 'not-an-array'], [], [], null, null, null, null];
        yield 'valuesWrongType' => [[$values => 'not-an-array'], [], [], null, null, null, null];
        yield 'valueIdsEmptyArray' => [[$valueIds => []], [], [], null, null, null, null];
        yield 'valueIdsAllValid' => [[$valueIds => [1, 2]], [1, 2], [], null, null, null, null];
        yield 'valueIdsAllInvalid' => [[$valueIds => ['a', 'b']], [], [], null, null, null, null];
        yield 'valuesEmptyArray' => [[$values => []], [], [], null, null, null, null];
        yield 'valuesAllValid' => [[$values => ['a', 'b']], [], ['a', 'b'], null, null, null, null];
        yield 'valuesAllInvalid' => [[$values => [1, 2]], [], [], null, null, null, null];
    }
}
