<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\UserAddress;
use ChristianBrown\Etsy\Model\UserAddressInterface;

use function is_bool;
use function is_int;
use function is_string;
use function sprintf;

final class UserAddressTransformer implements UserAddressTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): UserAddressInterface
    {
        if (!isset($data[self::KEY_USER_ADDRESS_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_USER_ADDRESS_ID));
        }
        if (!is_int($data[self::KEY_USER_ADDRESS_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_USER_ADDRESS_ID));
        }
        $userAddress = new UserAddress($data[self::KEY_USER_ADDRESS_ID]);

        self::applyCity($userAddress, $data);
        self::applyCountryName($userAddress, $data);
        self::applyFirstLine($userAddress, $data);
        self::applyIsDefaultShippingAddress($userAddress, $data);
        self::applyIsoCountryCode($userAddress, $data);
        self::applyName($userAddress, $data);
        self::applySecondLine($userAddress, $data);
        self::applyState($userAddress, $data);
        self::applyUserId($userAddress, $data);
        self::applyZip($userAddress, $data);

        return $userAddress;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCity(UserAddress $userAddress, array $data): void
    {
        if (empty($data[self::KEY_CITY])) {
            return;
        }
        if (!is_string($data[self::KEY_CITY])) {
            return;
        }
        $userAddress->setCity($data[self::KEY_CITY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCountryName(UserAddress $userAddress, array $data): void
    {
        if (empty($data[self::KEY_COUNTRY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_COUNTRY_NAME])) {
            return;
        }
        $userAddress->setCountryName($data[self::KEY_COUNTRY_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFirstLine(UserAddress $userAddress, array $data): void
    {
        if (empty($data[self::KEY_FIRST_LINE])) {
            return;
        }
        if (!is_string($data[self::KEY_FIRST_LINE])) {
            return;
        }
        $userAddress->setFirstLine($data[self::KEY_FIRST_LINE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsDefaultShippingAddress(UserAddress $userAddress, array $data): void
    {
        if (!isset($data[self::KEY_IS_DEFAULT_SHIPPING_ADDRESS])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_DEFAULT_SHIPPING_ADDRESS])) {
            return;
        }
        $userAddress->setIsDefaultShippingAddress($data[self::KEY_IS_DEFAULT_SHIPPING_ADDRESS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsoCountryCode(UserAddress $userAddress, array $data): void
    {
        if (empty($data[self::KEY_ISO_COUNTRY_CODE])) {
            return;
        }
        if (!is_string($data[self::KEY_ISO_COUNTRY_CODE])) {
            return;
        }
        $userAddress->setIsoCountryCode($data[self::KEY_ISO_COUNTRY_CODE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyName(UserAddress $userAddress, array $data): void
    {
        if (empty($data[self::KEY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_NAME])) {
            return;
        }
        $userAddress->setName($data[self::KEY_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySecondLine(UserAddress $userAddress, array $data): void
    {
        if (empty($data[self::KEY_SECOND_LINE])) {
            return;
        }
        if (!is_string($data[self::KEY_SECOND_LINE])) {
            return;
        }
        $userAddress->setSecondLine($data[self::KEY_SECOND_LINE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyState(UserAddress $userAddress, array $data): void
    {
        if (empty($data[self::KEY_STATE])) {
            return;
        }
        if (!is_string($data[self::KEY_STATE])) {
            return;
        }
        $userAddress->setState($data[self::KEY_STATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUserId(UserAddress $userAddress, array $data): void
    {
        if (!isset($data[self::KEY_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_USER_ID])) {
            return;
        }
        $userAddress->setUserId($data[self::KEY_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyZip(UserAddress $userAddress, array $data): void
    {
        if (empty($data[self::KEY_ZIP])) {
            return;
        }
        if (!is_string($data[self::KEY_ZIP])) {
            return;
        }
        $userAddress->setZip($data[self::KEY_ZIP]);
    }
}
