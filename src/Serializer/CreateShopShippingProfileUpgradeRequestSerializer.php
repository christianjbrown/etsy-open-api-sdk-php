<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileUpgradeRequestInterface;

final class CreateShopShippingProfileUpgradeRequestSerializer implements CreateShopShippingProfileUpgradeRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): array
    {
        $data = [];

        $data = self::applyMailClass($data, $createShopShippingProfileUpgradeRequest);
        $data = $this->applyMaxDeliveryDays($data, $createShopShippingProfileUpgradeRequest);
        $data = $this->applyMinDeliveryDays($data, $createShopShippingProfileUpgradeRequest);
        $data = $this->applyPrice($data, $createShopShippingProfileUpgradeRequest);
        $data = $this->applySecondaryPrice($data, $createShopShippingProfileUpgradeRequest);
        $data = $this->applyShippingCarrierId($data, $createShopShippingProfileUpgradeRequest);
        $data = $this->applyType($data, $createShopShippingProfileUpgradeRequest);
        $data = self::applyUpgradeName($data, $createShopShippingProfileUpgradeRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyMailClass(array $data, CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): array
    {
        $value = $createShopShippingProfileUpgradeRequest->getMailClass();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_MAIL_CLASS] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyMaxDeliveryDays(array $data, CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): array
    {
        $value = $createShopShippingProfileUpgradeRequest->getMaxDeliveryDays();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_MAX_DELIVERY_DAYS] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyMinDeliveryDays(array $data, CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): array
    {
        $value = $createShopShippingProfileUpgradeRequest->getMinDeliveryDays();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_MIN_DELIVERY_DAYS] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyPrice(array $data, CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): array
    {
        $data[self::KEY_PRICE] = $this->formValueEncoder->encodeFloat($createShopShippingProfileUpgradeRequest->getPrice());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applySecondaryPrice(array $data, CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): array
    {
        $data[self::KEY_SECONDARY_PRICE] = $this->formValueEncoder->encodeFloat($createShopShippingProfileUpgradeRequest->getSecondaryPrice());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyShippingCarrierId(array $data, CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): array
    {
        $value = $createShopShippingProfileUpgradeRequest->getShippingCarrierId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHIPPING_CARRIER_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyType(array $data, CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): array
    {
        $data[self::KEY_TYPE] = $this->formValueEncoder->encodeInt($createShopShippingProfileUpgradeRequest->getType());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyUpgradeName(array $data, CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): array
    {
        $data[self::KEY_UPGRADE_NAME] = $createShopShippingProfileUpgradeRequest->getUpgradeName();

        return $data;
    }
}
