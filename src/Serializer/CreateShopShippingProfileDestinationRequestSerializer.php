<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileDestinationRequestInterface;

final class CreateShopShippingProfileDestinationRequestSerializer implements CreateShopShippingProfileDestinationRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): array
    {
        $data = [];

        $data = self::applyDestinationCountryIso($data, $createShopShippingProfileDestinationRequest);
        $data = self::applyDestinationRegion($data, $createShopShippingProfileDestinationRequest);
        $data = self::applyMailClass($data, $createShopShippingProfileDestinationRequest);
        $data = $this->applyMaxDeliveryDays($data, $createShopShippingProfileDestinationRequest);
        $data = $this->applyMinDeliveryDays($data, $createShopShippingProfileDestinationRequest);
        $data = $this->applyPrimaryCost($data, $createShopShippingProfileDestinationRequest);
        $data = $this->applySecondaryCost($data, $createShopShippingProfileDestinationRequest);
        $data = $this->applyShippingCarrierId($data, $createShopShippingProfileDestinationRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyDestinationCountryIso(array $data, CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): array
    {
        $value = $createShopShippingProfileDestinationRequest->getDestinationCountryIso();
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
    private static function applyDestinationRegion(array $data, CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): array
    {
        $value = $createShopShippingProfileDestinationRequest->getDestinationRegion();
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
    private static function applyMailClass(array $data, CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): array
    {
        $value = $createShopShippingProfileDestinationRequest->getMailClass();
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
    private function applyMaxDeliveryDays(array $data, CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): array
    {
        $value = $createShopShippingProfileDestinationRequest->getMaxDeliveryDays();
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
    private function applyMinDeliveryDays(array $data, CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): array
    {
        $value = $createShopShippingProfileDestinationRequest->getMinDeliveryDays();
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
    private function applyPrimaryCost(array $data, CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): array
    {
        $data[self::KEY_PRIMARY_COST] = $this->formValueEncoder->encodeFloat($createShopShippingProfileDestinationRequest->getPrimaryCost());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applySecondaryCost(array $data, CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): array
    {
        $data[self::KEY_SECONDARY_COST] = $this->formValueEncoder->encodeFloat($createShopShippingProfileDestinationRequest->getSecondaryCost());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyShippingCarrierId(array $data, CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): array
    {
        $value = $createShopShippingProfileDestinationRequest->getShippingCarrierId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHIPPING_CARRIER_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }
}
