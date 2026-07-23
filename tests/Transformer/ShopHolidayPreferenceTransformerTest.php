<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\ShopHolidayPreference;
use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferenceTransformer;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferenceTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ShopHolidayPreference::class)]
#[CoversClass(ShopHolidayPreferenceTransformer::class)]
final class ShopHolidayPreferenceTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            ShopHolidayPreferenceTransformerInterface::KEY_COUNTRY_ISO => 'US',
            ShopHolidayPreferenceTransformerInterface::KEY_HOLIDAY_ID => 5,
            ShopHolidayPreferenceTransformerInterface::KEY_HOLIDAY_NAME => 'v_holidayName',
            ShopHolidayPreferenceTransformerInterface::KEY_IS_WORKING => true,
            ShopHolidayPreferenceTransformerInterface::KEY_SHOP_ID => 100,
        ];

        $transformer = new ShopHolidayPreferenceTransformer();

        $actual = $transformer->transform($data);

        self::assertSame('US', $actual->getCountryIso());
        self::assertSame(5, $actual->getHolidayId());
        self::assertSame('v_holidayName', $actual->getHolidayName());
        self::assertTrue($actual->getIsWorking());
        self::assertSame(100, $actual->getShopId());
    }

    /**
     * @param array<string, mixed>                          $data
     * @param Closure(ShopHolidayPreferenceInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ShopHolidayPreferenceTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ShopHolidayPreferenceInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        yield 'allOptionalAbsent' => [
            [],
            static function (ShopHolidayPreferenceInterface $model): void {
                self::assertNull($model->getCountryIso());
                self::assertNull($model->getHolidayId());
                self::assertNull($model->getHolidayName());
                self::assertNull($model->getIsWorking());
                self::assertNull($model->getShopId());
            },
        ];

        yield 'countryIsoWrongType' => [[ShopHolidayPreferenceTransformerInterface::KEY_COUNTRY_ISO => 42], static function (ShopHolidayPreferenceInterface $m): void {
            self::assertNull($m->getCountryIso());
        }];
        yield 'holidayIdWrongType' => [[ShopHolidayPreferenceTransformerInterface::KEY_HOLIDAY_ID => 'x'], static function (ShopHolidayPreferenceInterface $m): void {
            self::assertNull($m->getHolidayId());
        }];
        yield 'holidayNameWrongType' => [[ShopHolidayPreferenceTransformerInterface::KEY_HOLIDAY_NAME => 42], static function (ShopHolidayPreferenceInterface $m): void {
            self::assertNull($m->getHolidayName());
        }];
        yield 'isWorkingWrongType' => [[ShopHolidayPreferenceTransformerInterface::KEY_IS_WORKING => 'x'], static function (ShopHolidayPreferenceInterface $m): void {
            self::assertNull($m->getIsWorking());
        }];
        yield 'shopIdWrongType' => [[ShopHolidayPreferenceTransformerInterface::KEY_SHOP_ID => 'x'], static function (ShopHolidayPreferenceInterface $m): void {
            self::assertNull($m->getShopId());
        }];
    }
}
