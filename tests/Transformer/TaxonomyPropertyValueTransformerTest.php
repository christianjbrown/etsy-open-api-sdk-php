<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\TaxonomyPropertyValue;
use ChristianBrown\Etsy\Model\TaxonomyPropertyValueInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyValueTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyValueTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(TaxonomyPropertyValue::class)]
#[CoversClass(TaxonomyPropertyValueTransformer::class)]
final class TaxonomyPropertyValueTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            TaxonomyPropertyValueTransformerInterface::KEY_EQUAL_TO => [1, 2],
            TaxonomyPropertyValueTransformerInterface::KEY_NAME => 'n',
            TaxonomyPropertyValueTransformerInterface::KEY_SCALE_ID => 5,
            TaxonomyPropertyValueTransformerInterface::KEY_VALUE_ID => 7,
        ];

        $transformer = new TaxonomyPropertyValueTransformer();

        $actual = $transformer->transform($data);

        self::assertSame([1, 2], $actual->getEqualTo());
        self::assertSame('n', $actual->getName());
        self::assertSame(5, $actual->getScaleId());
        self::assertSame(7, $actual->getValueId());
    }

    /**
     * @param array<string, mixed>                          $data
     * @param Closure(TaxonomyPropertyValueInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new TaxonomyPropertyValueTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(TaxonomyPropertyValueInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        yield 'allOptionalAbsent' => [
            [],
            static function (TaxonomyPropertyValueInterface $m): void {
                self::assertSame([], $m->getEqualTo());
                self::assertNull($m->getName());
                self::assertNull($m->getScaleId());
                self::assertNull($m->getValueId());
            },
        ];

        yield 'equalToNotArray' => [[TaxonomyPropertyValueTransformerInterface::KEY_EQUAL_TO => 'x'], static function (TaxonomyPropertyValueInterface $m): void {
            self::assertSame([], $m->getEqualTo());
        }];
        yield 'equalToNonIntElement' => [[TaxonomyPropertyValueTransformerInterface::KEY_EQUAL_TO => [1, 'x', 2]], static function (TaxonomyPropertyValueInterface $m): void {
            self::assertSame([1, 2], $m->getEqualTo());
        }];
        yield 'equalToEmpty' => [[TaxonomyPropertyValueTransformerInterface::KEY_EQUAL_TO => []], static function (TaxonomyPropertyValueInterface $m): void {
            self::assertSame([], $m->getEqualTo());
        }];
        yield 'equalToAllInvalid' => [[TaxonomyPropertyValueTransformerInterface::KEY_EQUAL_TO => ['a', 'b']], static function (TaxonomyPropertyValueInterface $m): void {
            self::assertSame([], $m->getEqualTo());
        }];
        yield 'nameWrongType' => [[TaxonomyPropertyValueTransformerInterface::KEY_NAME => 42], static function (TaxonomyPropertyValueInterface $m): void {
            self::assertNull($m->getName());
        }];
        yield 'scaleIdWrongType' => [[TaxonomyPropertyValueTransformerInterface::KEY_SCALE_ID => 'x'], static function (TaxonomyPropertyValueInterface $m): void {
            self::assertNull($m->getScaleId());
        }];
        yield 'valueIdWrongType' => [[TaxonomyPropertyValueTransformerInterface::KEY_VALUE_ID => 'x'], static function (TaxonomyPropertyValueInterface $m): void {
            self::assertNull($m->getValueId());
        }];
    }
}
