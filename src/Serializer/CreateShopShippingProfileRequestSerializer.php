<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileRequestInterface;

final class CreateShopShippingProfileRequestSerializer implements CreateShopShippingProfileRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $data = [];

        $data = self::applyDestinationCountryIso($data, $createShopShippingProfileRequest);
        $data = self::applyDestinationRegion($data, $createShopShippingProfileRequest);
        $data = self::applyMailClass($data, $createShopShippingProfileRequest);
        $data = $this->applyMaxDeliveryDays($data, $createShopShippingProfileRequest);
        $data = $this->applyMaxProcessingTime($data, $createShopShippingProfileRequest);
        $data = $this->applyMinDeliveryDays($data, $createShopShippingProfileRequest);
        $data = $this->applyMinProcessingTime($data, $createShopShippingProfileRequest);
        $data = self::applyOriginCountryIso($data, $createShopShippingProfileRequest);
        $data = self::applyOriginPostalCode($data, $createShopShippingProfileRequest);
        $data = $this->applyPrimaryCost($data, $createShopShippingProfileRequest);
        $data = self::applyProcessingTimeUnit($data, $createShopShippingProfileRequest);
        $data = $this->applySecondaryCost($data, $createShopShippingProfileRequest);
        $data = $this->applyShippingCarrierId($data, $createShopShippingProfileRequest);
        $data = self::applyTitle($data, $createShopShippingProfileRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyDestinationCountryIso(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $value = $createShopShippingProfileRequest->getDestinationCountryIso();
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
    private static function applyDestinationRegion(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $value = $createShopShippingProfileRequest->getDestinationRegion();
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
    private static function applyMailClass(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $value = $createShopShippingProfileRequest->getMailClass();
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
    private function applyMaxDeliveryDays(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $value = $createShopShippingProfileRequest->getMaxDeliveryDays();
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
    private function applyMaxProcessingTime(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $value = $createShopShippingProfileRequest->getMaxProcessingTime();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_MAX_PROCESSING_TIME] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyMinDeliveryDays(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $value = $createShopShippingProfileRequest->getMinDeliveryDays();
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
    private function applyMinProcessingTime(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $value = $createShopShippingProfileRequest->getMinProcessingTime();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_MIN_PROCESSING_TIME] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyOriginCountryIso(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $data[self::KEY_ORIGIN_COUNTRY_ISO] = $createShopShippingProfileRequest->getOriginCountryIso();

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyOriginPostalCode(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $value = $createShopShippingProfileRequest->getOriginPostalCode();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ORIGIN_POSTAL_CODE] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyPrimaryCost(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $data[self::KEY_PRIMARY_COST] = $this->formValueEncoder->encodeFloat($createShopShippingProfileRequest->getPrimaryCost());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyProcessingTimeUnit(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $value = $createShopShippingProfileRequest->getProcessingTimeUnit();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_PROCESSING_TIME_UNIT] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applySecondaryCost(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $data[self::KEY_SECONDARY_COST] = $this->formValueEncoder->encodeFloat($createShopShippingProfileRequest->getSecondaryCost());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyShippingCarrierId(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $value = $createShopShippingProfileRequest->getShippingCarrierId();
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
    private static function applyTitle(array $data, CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): array
    {
        $data[self::KEY_TITLE] = $createShopShippingProfileRequest->getTitle();

        return $data;
    }
}
