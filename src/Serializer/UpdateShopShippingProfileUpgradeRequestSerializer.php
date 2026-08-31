<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileUpgradeRequestInterface;

final class UpdateShopShippingProfileUpgradeRequestSerializer implements UpdateShopShippingProfileUpgradeRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): array
    {
        $data = [];

        $data = self::applyMailClass($data, $updateShopShippingProfileUpgradeRequest);
        $data = $this->applyMaxDeliveryDays($data, $updateShopShippingProfileUpgradeRequest);
        $data = $this->applyMinDeliveryDays($data, $updateShopShippingProfileUpgradeRequest);
        $data = $this->applyPrice($data, $updateShopShippingProfileUpgradeRequest);
        $data = $this->applySecondaryPrice($data, $updateShopShippingProfileUpgradeRequest);
        $data = $this->applyShippingCarrierId($data, $updateShopShippingProfileUpgradeRequest);
        $data = $this->applyType($data, $updateShopShippingProfileUpgradeRequest);
        $data = self::applyUpgradeName($data, $updateShopShippingProfileUpgradeRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyMailClass(array $data, UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): array
    {
        $value = $updateShopShippingProfileUpgradeRequest->getMailClass();
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
    private function applyMaxDeliveryDays(array $data, UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): array
    {
        $value = $updateShopShippingProfileUpgradeRequest->getMaxDeliveryDays();
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
    private function applyMinDeliveryDays(array $data, UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): array
    {
        $value = $updateShopShippingProfileUpgradeRequest->getMinDeliveryDays();
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
    private function applyPrice(array $data, UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): array
    {
        $value = $updateShopShippingProfileUpgradeRequest->getPrice();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_PRICE] = $this->formValueEncoder->encodeFloat($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applySecondaryPrice(array $data, UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): array
    {
        $value = $updateShopShippingProfileUpgradeRequest->getSecondaryPrice();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SECONDARY_PRICE] = $this->formValueEncoder->encodeFloat($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyShippingCarrierId(array $data, UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): array
    {
        $value = $updateShopShippingProfileUpgradeRequest->getShippingCarrierId();
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
    private function applyType(array $data, UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): array
    {
        $value = $updateShopShippingProfileUpgradeRequest->getType();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_TYPE] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyUpgradeName(array $data, UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): array
    {
        $value = $updateShopShippingProfileUpgradeRequest->getUpgradeName();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_UPGRADE_NAME] = $value;

        return $data;
    }
}
