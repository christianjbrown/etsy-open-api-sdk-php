<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgrade;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradeTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradeTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopShippingProfileUpgrade::class)]
#[CoversClass(ShopShippingProfileUpgradeTransformer::class)]
final class ShopShippingProfileUpgradeTransformerTest extends TestCase
{
    public function testSetUpgradeId(): void
    {
        $upgrade = new ShopShippingProfileUpgrade(1);

        self::assertSame(2, $upgrade->setUpgradeId(2)->getUpgradeId());
    }

    public function testTransform(): void
    {
        $priceData = ['__price__'];
        $secondaryPriceData = ['__secondary_price__'];

        $data = [
            ShopShippingProfileUpgradeTransformerInterface::KEY_UPGRADE_ID => 9000,
            ShopShippingProfileUpgradeTransformerInterface::KEY_SHIPPING_PROFILE_ID => 11,
            ShopShippingProfileUpgradeTransformerInterface::KEY_UPGRADE_NAME => 'USPS Priority',
            ShopShippingProfileUpgradeTransformerInterface::KEY_TYPE => 1,
            ShopShippingProfileUpgradeTransformerInterface::KEY_RANK => 2,
            ShopShippingProfileUpgradeTransformerInterface::KEY_LANGUAGE => 'en',
            ShopShippingProfileUpgradeTransformerInterface::KEY_MAIL_CLASS => 'priority',
            ShopShippingProfileUpgradeTransformerInterface::KEY_MIN_DELIVERY_DAYS => 3,
            ShopShippingProfileUpgradeTransformerInterface::KEY_MAX_DELIVERY_DAYS => 7,
            ShopShippingProfileUpgradeTransformerInterface::KEY_SHIPPING_CARRIER_ID => 22,
            ShopShippingProfileUpgradeTransformerInterface::KEY_PRICE => $priceData,
            ShopShippingProfileUpgradeTransformerInterface::KEY_SECONDARY_PRICE => $secondaryPriceData,
        ];

        $price = self::createStub(MoneyInterface::class);
        $secondaryPrice = self::createStub(MoneyInterface::class);

        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);
        $moneyTransformer->method('transform')
            ->willReturnMap(
                [
                    [$priceData, $price],
                    [$secondaryPriceData, $secondaryPrice],
                ]
            );

        $transformer = new ShopShippingProfileUpgradeTransformer($moneyTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getUpgradeId());
        self::assertSame(11, $actual->getShippingProfileId());
        self::assertSame('USPS Priority', $actual->getUpgradeName());
        self::assertSame(1, $actual->getType());
        self::assertSame(2, $actual->getRank());
        self::assertSame('en', $actual->getLanguage());
        self::assertSame('priority', $actual->getMailClass());
        self::assertSame(3, $actual->getMinDeliveryDays());
        self::assertSame(7, $actual->getMaxDeliveryDays());
        self::assertSame(22, $actual->getShippingCarrierId());
        self::assertSame($price, $actual->getPrice());
        self::assertSame($secondaryPrice, $actual->getSecondaryPrice());
    }

    /**
     * @param array<string, mixed>                               $data
     * @param Closure(ShopShippingProfileUpgradeInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ShopShippingProfileUpgradeTransformer(self::createStub(MoneyTransformerInterface::class));

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ShopShippingProfileUpgradeInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ShopShippingProfileUpgradeTransformerInterface::KEY_UPGRADE_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ShopShippingProfileUpgradeInterface $model): void {
                self::assertNull($model->getShippingProfileId());
                self::assertNull($model->getUpgradeName());
                self::assertNull($model->getType());
                self::assertNull($model->getRank());
                self::assertNull($model->getLanguage());
                self::assertNull($model->getMailClass());
                self::assertNull($model->getMinDeliveryDays());
                self::assertNull($model->getMaxDeliveryDays());
                self::assertNull($model->getShippingCarrierId());
                self::assertNull($model->getPrice());
                self::assertNull($model->getSecondaryPrice());
            },
        ];

        yield 'shippingProfileIdWrongType' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_SHIPPING_PROFILE_ID => 'x'], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getShippingProfileId());
        }];
        yield 'upgradeNameWrongType' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_UPGRADE_NAME => 42], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getUpgradeName());
        }];
        yield 'typeWrongType' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_TYPE => 'x'], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getType());
        }];
        yield 'typeZero' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_TYPE => 0], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertSame(0, $m->getType());
        }];
        yield 'rankWrongType' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_RANK => 'x'], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getRank());
        }];
        yield 'languageWrongType' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_LANGUAGE => 42], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getLanguage());
        }];
        yield 'mailClassWrongType' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_MAIL_CLASS => 42], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getMailClass());
        }];
        yield 'minDeliveryDaysWrongType' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_MIN_DELIVERY_DAYS => 'x'], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getMinDeliveryDays());
        }];
        yield 'maxDeliveryDaysWrongType' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_MAX_DELIVERY_DAYS => 'x'], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getMaxDeliveryDays());
        }];
        yield 'shippingCarrierIdWrongType' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_SHIPPING_CARRIER_ID => 'x'], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getShippingCarrierId());
        }];
        yield 'priceNonArray' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_PRICE => 'x'], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getPrice());
        }];
        yield 'secondaryPriceNonArray' => [[$id => 1, ShopShippingProfileUpgradeTransformerInterface::KEY_SECONDARY_PRICE => 'x'], static function (ShopShippingProfileUpgradeInterface $m): void {
            self::assertNull($m->getSecondaryPrice());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ShopShippingProfileUpgradeTransformerInterface::KEY_UPGRADE_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidUpgradeId(array $data): void
    {
        $transformer = new ShopShippingProfileUpgradeTransformer(self::createStub(MoneyTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopShippingProfileUpgradeTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ShopShippingProfileUpgradeTransformerInterface::KEY_UPGRADE_ID));

        $transformer->transform($data);
    }
}
