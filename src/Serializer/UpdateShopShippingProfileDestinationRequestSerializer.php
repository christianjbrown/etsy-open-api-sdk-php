<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileDestinationRequestInterface;

final class UpdateShopShippingProfileDestinationRequestSerializer implements UpdateShopShippingProfileDestinationRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): array
    {
        $data = [];

        $data = self::applyDestinationCountryIso($data, $updateShopShippingProfileDestinationRequest);
        $data = self::applyDestinationRegion($data, $updateShopShippingProfileDestinationRequest);
        $data = self::applyMailClass($data, $updateShopShippingProfileDestinationRequest);
        $data = $this->applyMaxDeliveryDays($data, $updateShopShippingProfileDestinationRequest);
        $data = $this->applyMinDeliveryDays($data, $updateShopShippingProfileDestinationRequest);
        $data = $this->applyPrimaryCost($data, $updateShopShippingProfileDestinationRequest);
        $data = $this->applySecondaryCost($data, $updateShopShippingProfileDestinationRequest);
        $data = $this->applyShippingCarrierId($data, $updateShopShippingProfileDestinationRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyDestinationCountryIso(array $data, UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): array
    {
        $value = $updateShopShippingProfileDestinationRequest->getDestinationCountryIso();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_DESTINATION_COUNTRY_ISO] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyDestinationRegion(array $data, UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): array
    {
        $value = $updateShopShippingProfileDestinationRequest->getDestinationRegion();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_DESTINATION_REGION] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyMailClass(array $data, UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): array
    {
        $value = $updateShopShippingProfileDestinationRequest->getMailClass();
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
    private function applyMaxDeliveryDays(array $data, UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): array
    {
        $value = $updateShopShippingProfileDestinationRequest->getMaxDeliveryDays();
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
    private function applyMinDeliveryDays(array $data, UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): array
    {
        $value = $updateShopShippingProfileDestinationRequest->getMinDeliveryDays();
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
    private function applyPrimaryCost(array $data, UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): array
    {
        $value = $updateShopShippingProfileDestinationRequest->getPrimaryCost();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_PRIMARY_COST] = $this->formValueEncoder->encodeFloat($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applySecondaryCost(array $data, UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): array
    {
        $value = $updateShopShippingProfileDestinationRequest->getSecondaryCost();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SECONDARY_COST] = $this->formValueEncoder->encodeFloat($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyShippingCarrierId(array $data, UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): array
    {
        $value = $updateShopShippingProfileDestinationRequest->getShippingCarrierId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHIPPING_CARRIER_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }
}
