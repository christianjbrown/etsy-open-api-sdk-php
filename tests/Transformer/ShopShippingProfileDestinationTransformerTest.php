<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestination;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopShippingProfileDestination::class)]
#[CoversClass(ShopShippingProfileDestinationTransformer::class)]
final class ShopShippingProfileDestinationTransformerTest extends TestCase
{
    public function testSetShippingProfileDestinationId(): void
    {
        $destination = new ShopShippingProfileDestination(1);

        self::assertSame(2, $destination->setShippingProfileDestinationId(2)->getShippingProfileDestinationId());
    }

    public function testTransform(): void
    {
        $primaryCostData = ['__primary_cost__'];
        $secondaryCostData = ['__secondary_cost__'];

        $data = [
            ShopShippingProfileDestinationTransformerInterface::KEY_SHIPPING_PROFILE_DESTINATION_ID => 9000,
            ShopShippingProfileDestinationTransformerInterface::KEY_SHIPPING_PROFILE_ID => 11,
            ShopShippingProfileDestinationTransformerInterface::KEY_ORIGIN_COUNTRY_ISO => 'US',
            ShopShippingProfileDestinationTransformerInterface::KEY_DESTINATION_COUNTRY_ISO => 'GB',
            ShopShippingProfileDestinationTransformerInterface::KEY_DESTINATION_REGION => 'eu',
            ShopShippingProfileDestinationTransformerInterface::KEY_MAIL_CLASS => 'priority',
            ShopShippingProfileDestinationTransformerInterface::KEY_MIN_DELIVERY_DAYS => 3,
            ShopShippingProfileDestinationTransformerInterface::KEY_MAX_DELIVERY_DAYS => 7,
            ShopShippingProfileDestinationTransformerInterface::KEY_SHIPPING_CARRIER_ID => 22,
            ShopShippingProfileDestinationTransformerInterface::KEY_PRIMARY_COST => $primaryCostData,
            ShopShippingProfileDestinationTransformerInterface::KEY_SECONDARY_COST => $secondaryCostData,
        ];

        $primaryCost = self::createStub(MoneyInterface::class);
        $secondaryCost = self::createStub(MoneyInterface::class);

        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);
        $moneyTransformer->method('transform')
            ->willReturnMap(
                [
                    [$primaryCostData, $primaryCost],
                    [$secondaryCostData, $secondaryCost],
                ]
            );

        $transformer = new ShopShippingProfileDestinationTransformer($moneyTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getShippingProfileDestinationId());
        self::assertSame(11, $actual->getShippingProfileId());
        self::assertSame('US', $actual->getOriginCountryIso());
        self::assertSame('GB', $actual->getDestinationCountryIso());
        self::assertSame('eu', $actual->getDestinationRegion());
        self::assertSame('priority', $actual->getMailClass());
        self::assertSame(3, $actual->getMinDeliveryDays());
        self::assertSame(7, $actual->getMaxDeliveryDays());
        self::assertSame(22, $actual->getShippingCarrierId());
        self::assertSame($primaryCost, $actual->getPrimaryCost());
        self::assertSame($secondaryCost, $actual->getSecondaryCost());
    }

    /**
     * @param array<string, mixed>                                   $data
     * @param Closure(ShopShippingProfileDestinationInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ShopShippingProfileDestinationTransformer(self::createStub(MoneyTransformerInterface::class));

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ShopShippingProfileDestinationInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ShopShippingProfileDestinationTransformerInterface::KEY_SHIPPING_PROFILE_DESTINATION_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ShopShippingProfileDestinationInterface $model): void {
                self::assertNull($model->getShippingProfileId());
                self::assertNull($model->getOriginCountryIso());
                self::assertNull($model->getDestinationCountryIso());
                self::assertNull($model->getDestinationRegion());
                self::assertNull($model->getMailClass());
                self::assertNull($model->getMinDeliveryDays());
                self::assertNull($model->getMaxDeliveryDays());
                self::assertNull($model->getShippingCarrierId());
                self::assertNull($model->getPrimaryCost());
                self::assertNull($model->getSecondaryCost());
            },
        ];

        yield 'shippingProfileIdWrongType' => [[$id => 1, ShopShippingProfileDestinationTransformerInterface::KEY_SHIPPING_PROFILE_ID => 'x'], static function (ShopShippingProfileDestinationInterface $m): void {
            self::assertNull($m->getShippingProfileId());
        }];
        yield 'originCountryIsoWrongType' => [[$id => 1, ShopShippingProfileDestinationTransformerInterface::KEY_ORIGIN_COUNTRY_ISO => 42], static function (ShopShippingProfileDestinationInterface $m): void {
            self::assertNull($m->getOriginCountryIso());
        }];
        yield 'destinationCountryIsoWrongType' => [[$id => 1, ShopShippingProfileDestinationTransformerInterface::KEY_DESTINATION_COUNTRY_ISO => 42], static function (ShopShippingProfileDestinationInterface $m): void {
            self::assertNull($m->getDestinationCountryIso());
        }];
        yield 'destinationRegionWrongType' => [[$id => 1, ShopShippingProfileDestinationTransformerInterface::KEY_DESTINATION_REGION => 42], static function (ShopShippingProfileDestinationInterface $m): void {
            self::assertNull($m->getDestinationRegion());
        }];
        yield 'mailClassWrongType' => [[$id => 1, ShopShippingProfileDestinationTransformerInterface::KEY_MAIL_CLASS => 42], static function (ShopShippingProfileDestinationInterface $m): void {
            self::assertNull($m->getMailClass());
        }];
        yield 'minDeliveryDaysWrongType' => [[$id => 1, ShopShippingProfileDestinationTransformerInterface::KEY_MIN_DELIVERY_DAYS => 'x'], static function (ShopShippingProfileDestinationInterface $m): void {
            self::assertNull($m->getMinDeliveryDays());
        }];
        yield 'maxDeliveryDaysWrongType' => [[$id => 1, ShopShippingProfileDestinationTransformerInterface::KEY_MAX_DELIVERY_DAYS => 'x'], static function (ShopShippingProfileDestinationInterface $m): void {
            self::assertNull($m->getMaxDeliveryDays());
        }];
        yield 'shippingCarrierIdWrongType' => [[$id => 1, ShopShippingProfileDestinationTransformerInterface::KEY_SHIPPING_CARRIER_ID => 'x'], static function (ShopShippingProfileDestinationInterface $m): void {
            self::assertNull($m->getShippingCarrierId());
        }];
        yield 'primaryCostNonArray' => [[$id => 1, ShopShippingProfileDestinationTransformerInterface::KEY_PRIMARY_COST => 'x'], static function (ShopShippingProfileDestinationInterface $m): void {
            self::assertNull($m->getPrimaryCost());
        }];
        yield 'secondaryCostNonArray' => [[$id => 1, ShopShippingProfileDestinationTransformerInterface::KEY_SECONDARY_COST => 'x'], static function (ShopShippingProfileDestinationInterface $m): void {
            self::assertNull($m->getSecondaryCost());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ShopShippingProfileDestinationTransformerInterface::KEY_SHIPPING_PROFILE_DESTINATION_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidShippingProfileDestinationId(array $data): void
    {
        $transformer = new ShopShippingProfileDestinationTransformer(self::createStub(MoneyTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopShippingProfileDestinationTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ShopShippingProfileDestinationTransformerInterface::KEY_SHIPPING_PROFILE_DESTINATION_ID));

        $transformer->transform($data);
    }
}
