<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestination;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;

use function is_array;
use function is_int;
use function is_string;
use function sprintf;

final class ShopShippingProfileDestinationTransformer implements ShopShippingProfileDestinationTransformerInterface
{
    private MoneyTransformerInterface $moneyTransformer;

    public function __construct(MoneyTransformerInterface $moneyTransformer)
    {
        $this->moneyTransformer = $moneyTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopShippingProfileDestinationInterface
    {
        if (!isset($data[self::KEY_SHIPPING_PROFILE_DESTINATION_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_SHIPPING_PROFILE_DESTINATION_ID));
        }
        if (!is_int($data[self::KEY_SHIPPING_PROFILE_DESTINATION_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_SHIPPING_PROFILE_DESTINATION_ID));
        }
        $destination = new ShopShippingProfileDestination($data[self::KEY_SHIPPING_PROFILE_DESTINATION_ID]);

        self::applyDestinationCountryIso($destination, $data);
        self::applyDestinationRegion($destination, $data);
        self::applyMailClass($destination, $data);
        self::applyMaxDeliveryDays($destination, $data);
        self::applyMinDeliveryDays($destination, $data);
        self::applyOriginCountryIso($destination, $data);
        self::applyShippingCarrierId($destination, $data);
        self::applyShippingProfileId($destination, $data);
        $this->applyPrimaryCost($destination, $data);
        $this->applySecondaryCost($destination, $data);

        return $destination;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDestinationCountryIso(ShopShippingProfileDestination $destination, array $data): void
    {
        if (empty($data[self::KEY_DESTINATION_COUNTRY_ISO])) {
            return;
        }
        if (!is_string($data[self::KEY_DESTINATION_COUNTRY_ISO])) {
            return;
        }
        $destination->setDestinationCountryIso($data[self::KEY_DESTINATION_COUNTRY_ISO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDestinationRegion(ShopShippingProfileDestination $destination, array $data): void
    {
        if (empty($data[self::KEY_DESTINATION_REGION])) {
            return;
        }
        if (!is_string($data[self::KEY_DESTINATION_REGION])) {
            return;
        }
        $destination->setDestinationRegion($data[self::KEY_DESTINATION_REGION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMailClass(ShopShippingProfileDestination $destination, array $data): void
    {
        if (empty($data[self::KEY_MAIL_CLASS])) {
            return;
        }
        if (!is_string($data[self::KEY_MAIL_CLASS])) {
            return;
        }
        $destination->setMailClass($data[self::KEY_MAIL_CLASS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMaxDeliveryDays(ShopShippingProfileDestination $destination, array $data): void
    {
        if (!isset($data[self::KEY_MAX_DELIVERY_DAYS])) {
            return;
        }
        if (!is_int($data[self::KEY_MAX_DELIVERY_DAYS])) {
            return;
        }
        $destination->setMaxDeliveryDays($data[self::KEY_MAX_DELIVERY_DAYS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMinDeliveryDays(ShopShippingProfileDestination $destination, array $data): void
    {
        if (!isset($data[self::KEY_MIN_DELIVERY_DAYS])) {
            return;
        }
        if (!is_int($data[self::KEY_MIN_DELIVERY_DAYS])) {
            return;
        }
        $destination->setMinDeliveryDays($data[self::KEY_MIN_DELIVERY_DAYS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyOriginCountryIso(ShopShippingProfileDestination $destination, array $data): void
    {
        if (empty($data[self::KEY_ORIGIN_COUNTRY_ISO])) {
            return;
        }
        if (!is_string($data[self::KEY_ORIGIN_COUNTRY_ISO])) {
            return;
        }
        $destination->setOriginCountryIso($data[self::KEY_ORIGIN_COUNTRY_ISO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPrimaryCost(ShopShippingProfileDestination $destination, array $data): void
    {
        if (empty($data[self::KEY_PRIMARY_COST])) {
            return;
        }
        if (!is_array($data[self::KEY_PRIMARY_COST])) {
            return;
        }
        $destination->setPrimaryCost($this->moneyTransformer->transform($data[self::KEY_PRIMARY_COST]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applySecondaryCost(ShopShippingProfileDestination $destination, array $data): void
    {
        if (empty($data[self::KEY_SECONDARY_COST])) {
            return;
        }
        if (!is_array($data[self::KEY_SECONDARY_COST])) {
            return;
        }
        $destination->setSecondaryCost($this->moneyTransformer->transform($data[self::KEY_SECONDARY_COST]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingCarrierId(ShopShippingProfileDestination $destination, array $data): void
    {
        if (!isset($data[self::KEY_SHIPPING_CARRIER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPPING_CARRIER_ID])) {
            return;
        }
        $destination->setShippingCarrierId($data[self::KEY_SHIPPING_CARRIER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingProfileId(ShopShippingProfileDestination $destination, array $data): void
    {
        if (!isset($data[self::KEY_SHIPPING_PROFILE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPPING_PROFILE_ID])) {
            return;
        }
        $destination->setShippingProfileId($data[self::KEY_SHIPPING_PROFILE_ID]);
    }
}
