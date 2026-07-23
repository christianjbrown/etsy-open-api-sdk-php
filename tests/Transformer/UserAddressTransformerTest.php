<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\UserAddress;
use ChristianBrown\Etsy\Model\UserAddressInterface;
use ChristianBrown\Etsy\Transformer\UserAddressTransformer;
use ChristianBrown\Etsy\Transformer\UserAddressTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(UserAddress::class)]
#[CoversClass(UserAddressTransformer::class)]
final class UserAddressTransformerTest extends TestCase
{
    public function testSetUserAddressId(): void
    {
        $userAddress = new UserAddress(1);

        self::assertSame(2, $userAddress->setUserAddressId(2)->getUserAddressId());
    }

    public function testTransform(): void
    {
        $data = [
            UserAddressTransformerInterface::KEY_USER_ADDRESS_ID => 9000,
            UserAddressTransformerInterface::KEY_CITY => 'v_city',
            UserAddressTransformerInterface::KEY_COUNTRY_NAME => 'v_countryName',
            UserAddressTransformerInterface::KEY_FIRST_LINE => 'v_firstLine',
            UserAddressTransformerInterface::KEY_IS_DEFAULT_SHIPPING_ADDRESS => true,
            UserAddressTransformerInterface::KEY_ISO_COUNTRY_CODE => 'v_isoCountryCode',
            UserAddressTransformerInterface::KEY_NAME => 'v_name',
            UserAddressTransformerInterface::KEY_SECOND_LINE => 'v_secondLine',
            UserAddressTransformerInterface::KEY_STATE => 'v_state',
            UserAddressTransformerInterface::KEY_USER_ID => 100,
            UserAddressTransformerInterface::KEY_ZIP => 'v_zip',
        ];

        $transformer = new UserAddressTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getUserAddressId());
        self::assertSame('v_city', $actual->getCity());
        self::assertSame('v_countryName', $actual->getCountryName());
        self::assertSame('v_firstLine', $actual->getFirstLine());
        self::assertTrue($actual->getIsDefaultShippingAddress());
        self::assertSame('v_isoCountryCode', $actual->getIsoCountryCode());
        self::assertSame('v_name', $actual->getName());
        self::assertSame('v_secondLine', $actual->getSecondLine());
        self::assertSame('v_state', $actual->getState());
        self::assertSame(100, $actual->getUserId());
        self::assertSame('v_zip', $actual->getZip());
    }

    /**
     * @param array<string, mixed>                $data
     * @param Closure(UserAddressInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new UserAddressTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(UserAddressInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = UserAddressTransformerInterface::KEY_USER_ADDRESS_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (UserAddressInterface $model): void {
                self::assertNull($model->getCity());
                self::assertNull($model->getIsDefaultShippingAddress());
                self::assertNull($model->getUserId());
            },
        ];

        yield 'cityWrongType' => [[$id => 1, UserAddressTransformerInterface::KEY_CITY => 42], static function (UserAddressInterface $m): void {
            self::assertNull($m->getCity());
        }];
        yield 'countryNameWrongType' => [[$id => 1, UserAddressTransformerInterface::KEY_COUNTRY_NAME => 42], static function (UserAddressInterface $m): void {
            self::assertNull($m->getCountryName());
        }];
        yield 'firstLineWrongType' => [[$id => 1, UserAddressTransformerInterface::KEY_FIRST_LINE => 42], static function (UserAddressInterface $m): void {
            self::assertNull($m->getFirstLine());
        }];
        yield 'isDefaultShippingAddressWrongType' => [[$id => 1, UserAddressTransformerInterface::KEY_IS_DEFAULT_SHIPPING_ADDRESS => 'x'], static function (UserAddressInterface $m): void {
            self::assertNull($m->getIsDefaultShippingAddress());
        }];
        yield 'isoCountryCodeWrongType' => [[$id => 1, UserAddressTransformerInterface::KEY_ISO_COUNTRY_CODE => 42], static function (UserAddressInterface $m): void {
            self::assertNull($m->getIsoCountryCode());
        }];
        yield 'nameWrongType' => [[$id => 1, UserAddressTransformerInterface::KEY_NAME => 42], static function (UserAddressInterface $m): void {
            self::assertNull($m->getName());
        }];
        yield 'secondLineWrongType' => [[$id => 1, UserAddressTransformerInterface::KEY_SECOND_LINE => 42], static function (UserAddressInterface $m): void {
            self::assertNull($m->getSecondLine());
        }];
        yield 'stateWrongType' => [[$id => 1, UserAddressTransformerInterface::KEY_STATE => 42], static function (UserAddressInterface $m): void {
            self::assertNull($m->getState());
        }];
        yield 'userIdWrongType' => [[$id => 1, UserAddressTransformerInterface::KEY_USER_ID => 'x'], static function (UserAddressInterface $m): void {
            self::assertNull($m->getUserId());
        }];
        yield 'zipWrongType' => [[$id => 1, UserAddressTransformerInterface::KEY_ZIP => 42], static function (UserAddressInterface $m): void {
            self::assertNull($m->getZip());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[UserAddressTransformerInterface::KEY_USER_ADDRESS_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidUserAddressId(array $data): void
    {
        $transformer = new UserAddressTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(UserAddressTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, UserAddressTransformerInterface::KEY_USER_ADDRESS_ID));

        $transformer->transform($data);
    }
}
