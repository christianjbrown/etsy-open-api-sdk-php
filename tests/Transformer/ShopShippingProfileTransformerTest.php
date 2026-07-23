<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopShippingProfile;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradesTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopShippingProfile::class)]
#[CoversClass(ShopShippingProfileTransformer::class)]
final class ShopShippingProfileTransformerTest extends TestCase
{
    public function testSetShippingProfileId(): void
    {
        $shippingProfile = new ShopShippingProfile(1);

        self::assertSame(2, $shippingProfile->setShippingProfileId(2)->getShippingProfileId());
    }

    public function testTransform(): void
    {
        $destinationsData = ['__destinations__'];
        $upgradesData = ['__upgrades__'];

        $data = [
            ShopShippingProfileTransformerInterface::KEY_SHIPPING_PROFILE_ID => 9000,
            ShopShippingProfileTransformerInterface::KEY_TITLE => 'Standard',
            ShopShippingProfileTransformerInterface::KEY_USER_ID => 11,
            ShopShippingProfileTransformerInterface::KEY_ORIGIN_COUNTRY_ISO => 'US',
            ShopShippingProfileTransformerInterface::KEY_IS_DELETED => true,
            ShopShippingProfileTransformerInterface::KEY_ORIGIN_POSTAL_CODE => '90210',
            ShopShippingProfileTransformerInterface::KEY_PROFILE_TYPE => 'manual',
            ShopShippingProfileTransformerInterface::KEY_DOMESTIC_HANDLING_FEE => 4.5,
            ShopShippingProfileTransformerInterface::KEY_INTERNATIONAL_HANDLING_FEE => 6.75,
            ShopShippingProfileTransformerInterface::KEY_SHIPPING_PROFILE_DESTINATIONS => $destinationsData,
            ShopShippingProfileTransformerInterface::KEY_SHIPPING_PROFILE_UPGRADES => $upgradesData,
        ];

        $destination = self::createStub(ShopShippingProfileDestinationInterface::class);
        $upgrade = self::createStub(ShopShippingProfileUpgradeInterface::class);

        $destinationsTransformer = self::createMock(ShopShippingProfileDestinationsTransformerInterface::class);
        $destinationsTransformer->expects(self::once())->method('transform')
            ->with($destinationsData)
            ->willReturn([$destination]);

        $upgradesTransformer = self::createMock(ShopShippingProfileUpgradesTransformerInterface::class);
        $upgradesTransformer->expects(self::once())->method('transform')
            ->with($upgradesData)
            ->willReturn([$upgrade]);

        $transformer = new ShopShippingProfileTransformer($destinationsTransformer, $upgradesTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getShippingProfileId());
        self::assertSame('Standard', $actual->getTitle());
        self::assertSame(11, $actual->getUserId());
        self::assertSame('US', $actual->getOriginCountryIso());
        self::assertTrue($actual->getIsDeleted());
        self::assertSame('90210', $actual->getOriginPostalCode());
        self::assertSame('manual', $actual->getProfileType());
        self::assertSame(4.5, $actual->getDomesticHandlingFee());
        self::assertSame(6.75, $actual->getInternationalHandlingFee());
        self::assertSame([$destination], $actual->getShippingProfileDestinations());
        self::assertSame([$upgrade], $actual->getShippingProfileUpgrades());
    }

    /**
     * @param array<string, mixed>                        $data
     * @param Closure(ShopShippingProfileInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ShopShippingProfileTransformer(
            self::createStub(ShopShippingProfileDestinationsTransformerInterface::class),
            self::createStub(ShopShippingProfileUpgradesTransformerInterface::class),
        );

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ShopShippingProfileInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ShopShippingProfileTransformerInterface::KEY_SHIPPING_PROFILE_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ShopShippingProfileInterface $model): void {
                self::assertNull($model->getTitle());
                self::assertNull($model->getUserId());
                self::assertNull($model->getOriginCountryIso());
                self::assertNull($model->getIsDeleted());
                self::assertNull($model->getOriginPostalCode());
                self::assertNull($model->getProfileType());
                self::assertNull($model->getDomesticHandlingFee());
                self::assertNull($model->getInternationalHandlingFee());
                self::assertSame([], $model->getShippingProfileDestinations());
                self::assertSame([], $model->getShippingProfileUpgrades());
            },
        ];

        yield 'titleWrongType' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_TITLE => 42], static function (ShopShippingProfileInterface $m): void {
            self::assertNull($m->getTitle());
        }];
        yield 'userIdWrongType' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_USER_ID => 'x'], static function (ShopShippingProfileInterface $m): void {
            self::assertNull($m->getUserId());
        }];
        yield 'originCountryIsoWrongType' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_ORIGIN_COUNTRY_ISO => 42], static function (ShopShippingProfileInterface $m): void {
            self::assertNull($m->getOriginCountryIso());
        }];
        yield 'isDeletedWrongType' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_IS_DELETED => 'x'], static function (ShopShippingProfileInterface $m): void {
            self::assertNull($m->getIsDeleted());
        }];
        yield 'isDeletedFalse' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_IS_DELETED => false], static function (ShopShippingProfileInterface $m): void {
            self::assertFalse($m->getIsDeleted());
        }];
        yield 'originPostalCodeWrongType' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_ORIGIN_POSTAL_CODE => 42], static function (ShopShippingProfileInterface $m): void {
            self::assertNull($m->getOriginPostalCode());
        }];
        yield 'profileTypeWrongType' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_PROFILE_TYPE => 42], static function (ShopShippingProfileInterface $m): void {
            self::assertNull($m->getProfileType());
        }];
        yield 'domesticHandlingFeeInt' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_DOMESTIC_HANDLING_FEE => 5], static function (ShopShippingProfileInterface $m): void {
            self::assertSame(5.0, $m->getDomesticHandlingFee());
        }];
        yield 'domesticHandlingFeeWrongType' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_DOMESTIC_HANDLING_FEE => 'x'], static function (ShopShippingProfileInterface $m): void {
            self::assertNull($m->getDomesticHandlingFee());
        }];
        yield 'internationalHandlingFeeInt' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_INTERNATIONAL_HANDLING_FEE => 8], static function (ShopShippingProfileInterface $m): void {
            self::assertSame(8.0, $m->getInternationalHandlingFee());
        }];
        yield 'internationalHandlingFeeWrongType' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_INTERNATIONAL_HANDLING_FEE => 'x'], static function (ShopShippingProfileInterface $m): void {
            self::assertNull($m->getInternationalHandlingFee());
        }];
        yield 'shippingProfileDestinationsNonArray' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_SHIPPING_PROFILE_DESTINATIONS => 'x'], static function (ShopShippingProfileInterface $m): void {
            self::assertSame([], $m->getShippingProfileDestinations());
        }];
        yield 'shippingProfileUpgradesNonArray' => [[$id => 1, ShopShippingProfileTransformerInterface::KEY_SHIPPING_PROFILE_UPGRADES => 'x'], static function (ShopShippingProfileInterface $m): void {
            self::assertSame([], $m->getShippingProfileUpgrades());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ShopShippingProfileTransformerInterface::KEY_SHIPPING_PROFILE_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidShippingProfileId(array $data): void
    {
        $transformer = new ShopShippingProfileTransformer(
            self::createStub(ShopShippingProfileDestinationsTransformerInterface::class),
            self::createStub(ShopShippingProfileUpgradesTransformerInterface::class),
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopShippingProfileTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ShopShippingProfileTransformerInterface::KEY_SHIPPING_PROFILE_ID));

        $transformer->transform($data);
    }
}
