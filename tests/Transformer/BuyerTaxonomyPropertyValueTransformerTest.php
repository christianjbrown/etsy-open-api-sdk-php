<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyValue;
use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyValueInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyValueTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyValueTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(BuyerTaxonomyPropertyValue::class)]
#[CoversClass(BuyerTaxonomyPropertyValueTransformer::class)]
final class BuyerTaxonomyPropertyValueTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            BuyerTaxonomyPropertyValueTransformerInterface::KEY_EQUAL_TO => [1, 2],
            BuyerTaxonomyPropertyValueTransformerInterface::KEY_NAME => 'n',
            BuyerTaxonomyPropertyValueTransformerInterface::KEY_SCALE_ID => 5,
            BuyerTaxonomyPropertyValueTransformerInterface::KEY_VALUE_ID => 7,
        ];

        $transformer = new BuyerTaxonomyPropertyValueTransformer();

        $actual = $transformer->transform($data);

        self::assertSame([1, 2], $actual->getEqualTo());
        self::assertSame('n', $actual->getName());
        self::assertSame(5, $actual->getScaleId());
        self::assertSame(7, $actual->getValueId());
    }

    /**
     * @param array<string, mixed>                               $data
     * @param Closure(BuyerTaxonomyPropertyValueInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new BuyerTaxonomyPropertyValueTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(BuyerTaxonomyPropertyValueInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        yield 'allOptionalAbsent' => [
            [],
            static function (BuyerTaxonomyPropertyValueInterface $m): void {
                self::assertSame([], $m->getEqualTo());
                self::assertNull($m->getName());
                self::assertNull($m->getScaleId());
                self::assertNull($m->getValueId());
            },
        ];

        yield 'equalToNotArray' => [[BuyerTaxonomyPropertyValueTransformerInterface::KEY_EQUAL_TO => 'x'], static function (BuyerTaxonomyPropertyValueInterface $m): void {
            self::assertSame([], $m->getEqualTo());
        }];
        yield 'equalToNonIntElement' => [[BuyerTaxonomyPropertyValueTransformerInterface::KEY_EQUAL_TO => [1, 'x', 2]], static function (BuyerTaxonomyPropertyValueInterface $m): void {
            self::assertSame([1, 2], $m->getEqualTo());
        }];
        yield 'equalToEmpty' => [[BuyerTaxonomyPropertyValueTransformerInterface::KEY_EQUAL_TO => []], static function (BuyerTaxonomyPropertyValueInterface $m): void {
            self::assertSame([], $m->getEqualTo());
        }];
        yield 'equalToAllInvalid' => [[BuyerTaxonomyPropertyValueTransformerInterface::KEY_EQUAL_TO => ['a', 'b']], static function (BuyerTaxonomyPropertyValueInterface $m): void {
            self::assertSame([], $m->getEqualTo());
        }];
        yield 'nameWrongType' => [[BuyerTaxonomyPropertyValueTransformerInterface::KEY_NAME => 42], static function (BuyerTaxonomyPropertyValueInterface $m): void {
            self::assertNull($m->getName());
        }];
        yield 'scaleIdWrongType' => [[BuyerTaxonomyPropertyValueTransformerInterface::KEY_SCALE_ID => 'x'], static function (BuyerTaxonomyPropertyValueInterface $m): void {
            self::assertNull($m->getScaleId());
        }];
        yield 'valueIdWrongType' => [[BuyerTaxonomyPropertyValueTransformerInterface::KEY_VALUE_ID => 'x'], static function (BuyerTaxonomyPropertyValueInterface $m): void {
            self::assertNull($m->getValueId());
        }];
    }
}
