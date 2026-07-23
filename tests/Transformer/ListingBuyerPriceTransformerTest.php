<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\ListingBuyerPrice;
use ChristianBrown\Etsy\Model\ListingBuyerPriceInterface;
use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Transformer\ListingBuyerPriceTransformer;
use ChristianBrown\Etsy\Transformer\ListingBuyerPriceTransformerInterface;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingBuyerPrice::class)]
#[CoversClass(ListingBuyerPriceTransformer::class)]
final class ListingBuyerPriceTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $basePriceData = ['__basePrice__'];
        $basePrice = self::createStub(MoneyInterface::class);
        $shippingCostData = ['__shippingCost__'];
        $shippingCost = self::createStub(MoneyInterface::class);
        $originalPriceData = ['__originalPrice__'];
        $originalPrice = self::createStub(MoneyInterface::class);
        $discountedPriceData = ['__discountedPrice__'];
        $discountedPrice = self::createStub(MoneyInterface::class);
        $discountAmountData = ['__discountAmount__'];
        $discountAmount = self::createStub(MoneyInterface::class);

        $data = [
            ListingBuyerPriceTransformerInterface::KEY_BASE_PRICE => $basePriceData,
            ListingBuyerPriceTransformerInterface::KEY_SHIPPING_COST => $shippingCostData,
            ListingBuyerPriceTransformerInterface::KEY_ORIGINAL_PRICE => $originalPriceData,
            ListingBuyerPriceTransformerInterface::KEY_DISCOUNTED_PRICE => $discountedPriceData,
            ListingBuyerPriceTransformerInterface::KEY_DISCOUNT_AMOUNT => $discountAmountData,
            ListingBuyerPriceTransformerInterface::KEY_IS_FREE_SHIPPING => true,
            ListingBuyerPriceTransformerInterface::KEY_HAS_DISCOUNT => true,
            ListingBuyerPriceTransformerInterface::KEY_DISCOUNT_PERCENTAGE => 20,
            ListingBuyerPriceTransformerInterface::KEY_DISCOUNT_START_EPOCH => 1600000000,
            ListingBuyerPriceTransformerInterface::KEY_DISCOUNT_END_EPOCH => 1700000000,
        ];

        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);
        $moneyTransformer->method('transform')
            ->willReturnMap(
                [
                    [$basePriceData, $basePrice],
                    [$shippingCostData, $shippingCost],
                    [$originalPriceData, $originalPrice],
                    [$discountedPriceData, $discountedPrice],
                    [$discountAmountData, $discountAmount],
                ]
            );

        $transformer = new ListingBuyerPriceTransformer($moneyTransformer);

        $actual = $transformer->transform($data);

        self::assertSame($basePrice, $actual->getBasePrice());
        self::assertSame($shippingCost, $actual->getShippingCost());
        self::assertSame($originalPrice, $actual->getOriginalPrice());
        self::assertSame($discountedPrice, $actual->getDiscountedPrice());
        self::assertSame($discountAmount, $actual->getDiscountAmount());
        self::assertTrue($actual->getIsFreeShipping());
        self::assertTrue($actual->getHasDiscount());
        self::assertSame(20, $actual->getDiscountPercentage());
        self::assertSame(1600000000, $actual->getDiscountStartEpoch());
        self::assertSame(1700000000, $actual->getDiscountEndEpoch());
    }

    /**
     * @param array<string, mixed>                      $data
     * @param Closure(ListingBuyerPriceInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);

        $transformer = new ListingBuyerPriceTransformer($moneyTransformer);

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ListingBuyerPriceInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        yield 'allOptionalAbsent' => [
            [],
            static function (ListingBuyerPriceInterface $m): void {
                self::assertNull($m->getBasePrice());
                self::assertNull($m->getShippingCost());
                self::assertNull($m->getOriginalPrice());
                self::assertNull($m->getDiscountedPrice());
                self::assertNull($m->getDiscountAmount());
                self::assertNull($m->getIsFreeShipping());
                self::assertNull($m->getHasDiscount());
                self::assertNull($m->getDiscountPercentage());
                self::assertNull($m->getDiscountStartEpoch());
                self::assertNull($m->getDiscountEndEpoch());
            },
        ];

        yield 'basePriceWrongType' => [[ListingBuyerPriceTransformerInterface::KEY_BASE_PRICE => 'x'], static function (ListingBuyerPriceInterface $m): void {
            self::assertNull($m->getBasePrice());
        }];
        yield 'shippingCostWrongType' => [[ListingBuyerPriceTransformerInterface::KEY_SHIPPING_COST => 'x'], static function (ListingBuyerPriceInterface $m): void {
            self::assertNull($m->getShippingCost());
        }];
        yield 'originalPriceWrongType' => [[ListingBuyerPriceTransformerInterface::KEY_ORIGINAL_PRICE => 'x'], static function (ListingBuyerPriceInterface $m): void {
            self::assertNull($m->getOriginalPrice());
        }];
        yield 'discountedPriceWrongType' => [[ListingBuyerPriceTransformerInterface::KEY_DISCOUNTED_PRICE => 'x'], static function (ListingBuyerPriceInterface $m): void {
            self::assertNull($m->getDiscountedPrice());
        }];
        yield 'discountAmountWrongType' => [[ListingBuyerPriceTransformerInterface::KEY_DISCOUNT_AMOUNT => 'x'], static function (ListingBuyerPriceInterface $m): void {
            self::assertNull($m->getDiscountAmount());
        }];
        yield 'isFreeShippingWrongType' => [[ListingBuyerPriceTransformerInterface::KEY_IS_FREE_SHIPPING => 'x'], static function (ListingBuyerPriceInterface $m): void {
            self::assertNull($m->getIsFreeShipping());
        }];
        yield 'hasDiscountWrongType' => [[ListingBuyerPriceTransformerInterface::KEY_HAS_DISCOUNT => 'x'], static function (ListingBuyerPriceInterface $m): void {
            self::assertNull($m->getHasDiscount());
        }];
        yield 'discountPercentageWrongType' => [[ListingBuyerPriceTransformerInterface::KEY_DISCOUNT_PERCENTAGE => 'x'], static function (ListingBuyerPriceInterface $m): void {
            self::assertNull($m->getDiscountPercentage());
        }];
        yield 'discountStartEpochWrongType' => [[ListingBuyerPriceTransformerInterface::KEY_DISCOUNT_START_EPOCH => 'x'], static function (ListingBuyerPriceInterface $m): void {
            self::assertNull($m->getDiscountStartEpoch());
        }];
        yield 'discountEndEpochWrongType' => [[ListingBuyerPriceTransformerInterface::KEY_DISCOUNT_END_EPOCH => 'x'], static function (ListingBuyerPriceInterface $m): void {
            self::assertNull($m->getDiscountEndEpoch());
        }];
    }
}
