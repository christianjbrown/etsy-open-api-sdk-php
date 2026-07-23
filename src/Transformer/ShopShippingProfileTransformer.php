<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopShippingProfile;
use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;

use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function sprintf;

final class ShopShippingProfileTransformer implements ShopShippingProfileTransformerInterface
{
    private ShopShippingProfileDestinationsTransformerInterface $shopShippingProfileDestinationsTransformer;
    private ShopShippingProfileUpgradesTransformerInterface $shopShippingProfileUpgradesTransformer;

    public function __construct(ShopShippingProfileDestinationsTransformerInterface $shopShippingProfileDestinationsTransformer, ShopShippingProfileUpgradesTransformerInterface $shopShippingProfileUpgradesTransformer)
    {
        $this->shopShippingProfileDestinationsTransformer = $shopShippingProfileDestinationsTransformer;
        $this->shopShippingProfileUpgradesTransformer = $shopShippingProfileUpgradesTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopShippingProfileInterface
    {
        if (!isset($data[self::KEY_SHIPPING_PROFILE_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_SHIPPING_PROFILE_ID));
        }
        if (!is_int($data[self::KEY_SHIPPING_PROFILE_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_SHIPPING_PROFILE_ID));
        }
        $shippingProfile = new ShopShippingProfile($data[self::KEY_SHIPPING_PROFILE_ID]);

        self::applyDomesticHandlingFee($shippingProfile, $data);
        self::applyInternationalHandlingFee($shippingProfile, $data);
        self::applyIsDeleted($shippingProfile, $data);
        self::applyOriginCountryIso($shippingProfile, $data);
        self::applyOriginPostalCode($shippingProfile, $data);
        self::applyProfileType($shippingProfile, $data);
        self::applyTitle($shippingProfile, $data);
        self::applyUserId($shippingProfile, $data);
        $this->applyShippingProfileDestinations($shippingProfile, $data);
        $this->applyShippingProfileUpgrades($shippingProfile, $data);

        return $shippingProfile;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDomesticHandlingFee(ShopShippingProfile $shippingProfile, array $data): void
    {
        if (!isset($data[self::KEY_DOMESTIC_HANDLING_FEE])) {
            return;
        }
        $value = self::toFloat($data[self::KEY_DOMESTIC_HANDLING_FEE]);
        if (null === $value) {
            return;
        }
        $shippingProfile->setDomesticHandlingFee($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyInternationalHandlingFee(ShopShippingProfile $shippingProfile, array $data): void
    {
        if (!isset($data[self::KEY_INTERNATIONAL_HANDLING_FEE])) {
            return;
        }
        $value = self::toFloat($data[self::KEY_INTERNATIONAL_HANDLING_FEE]);
        if (null === $value) {
            return;
        }
        $shippingProfile->setInternationalHandlingFee($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsDeleted(ShopShippingProfile $shippingProfile, array $data): void
    {
        if (!isset($data[self::KEY_IS_DELETED])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_DELETED])) {
            return;
        }
        $shippingProfile->setIsDeleted($data[self::KEY_IS_DELETED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyOriginCountryIso(ShopShippingProfile $shippingProfile, array $data): void
    {
        if (empty($data[self::KEY_ORIGIN_COUNTRY_ISO])) {
            return;
        }
        if (!is_string($data[self::KEY_ORIGIN_COUNTRY_ISO])) {
            return;
        }
        $shippingProfile->setOriginCountryIso($data[self::KEY_ORIGIN_COUNTRY_ISO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyOriginPostalCode(ShopShippingProfile $shippingProfile, array $data): void
    {
        if (empty($data[self::KEY_ORIGIN_POSTAL_CODE])) {
            return;
        }
        if (!is_string($data[self::KEY_ORIGIN_POSTAL_CODE])) {
            return;
        }
        $shippingProfile->setOriginPostalCode($data[self::KEY_ORIGIN_POSTAL_CODE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyProfileType(ShopShippingProfile $shippingProfile, array $data): void
    {
        if (empty($data[self::KEY_PROFILE_TYPE])) {
            return;
        }
        if (!is_string($data[self::KEY_PROFILE_TYPE])) {
            return;
        }
        $shippingProfile->setProfileType($data[self::KEY_PROFILE_TYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyShippingProfileDestinations(ShopShippingProfile $shippingProfile, array $data): void
    {
        if (empty($data[self::KEY_SHIPPING_PROFILE_DESTINATIONS])) {
            return;
        }
        if (!is_array($data[self::KEY_SHIPPING_PROFILE_DESTINATIONS])) {
            return;
        }
        $shippingProfile->setShippingProfileDestinations($this->shopShippingProfileDestinationsTransformer->transform($data[self::KEY_SHIPPING_PROFILE_DESTINATIONS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyShippingProfileUpgrades(ShopShippingProfile $shippingProfile, array $data): void
    {
        if (empty($data[self::KEY_SHIPPING_PROFILE_UPGRADES])) {
            return;
        }
        if (!is_array($data[self::KEY_SHIPPING_PROFILE_UPGRADES])) {
            return;
        }
        $shippingProfile->setShippingProfileUpgrades($this->shopShippingProfileUpgradesTransformer->transform($data[self::KEY_SHIPPING_PROFILE_UPGRADES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTitle(ShopShippingProfile $shippingProfile, array $data): void
    {
        if (empty($data[self::KEY_TITLE])) {
            return;
        }
        if (!is_string($data[self::KEY_TITLE])) {
            return;
        }
        $shippingProfile->setTitle($data[self::KEY_TITLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUserId(ShopShippingProfile $shippingProfile, array $data): void
    {
        if (!isset($data[self::KEY_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_USER_ID])) {
            return;
        }
        $shippingProfile->setUserId($data[self::KEY_USER_ID]);
    }

    private static function toFloat(mixed $value): ?float
    {
        if (is_int($value)) {
            return (float) $value;
        }
        if (is_float($value)) {
            return $value;
        }

        return null;
    }
}
