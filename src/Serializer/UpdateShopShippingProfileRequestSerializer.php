<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileRequestInterface;

final class UpdateShopShippingProfileRequestSerializer implements UpdateShopShippingProfileRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateShopShippingProfileRequestInterface $updateShopShippingProfileRequest): array
    {
        $data = [];

        $data = $this->applyMaxProcessingTime($data, $updateShopShippingProfileRequest);
        $data = $this->applyMinProcessingTime($data, $updateShopShippingProfileRequest);
        $data = self::applyOriginCountryIso($data, $updateShopShippingProfileRequest);
        $data = self::applyOriginPostalCode($data, $updateShopShippingProfileRequest);
        $data = self::applyProcessingTimeUnit($data, $updateShopShippingProfileRequest);
        $data = self::applyTitle($data, $updateShopShippingProfileRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyMaxProcessingTime(array $data, UpdateShopShippingProfileRequestInterface $updateShopShippingProfileRequest): array
    {
        $value = $updateShopShippingProfileRequest->getMaxProcessingTime();
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
    private function applyMinProcessingTime(array $data, UpdateShopShippingProfileRequestInterface $updateShopShippingProfileRequest): array
    {
        $value = $updateShopShippingProfileRequest->getMinProcessingTime();
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
    private static function applyOriginCountryIso(array $data, UpdateShopShippingProfileRequestInterface $updateShopShippingProfileRequest): array
    {
        $value = $updateShopShippingProfileRequest->getOriginCountryIso();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ORIGIN_COUNTRY_ISO] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyOriginPostalCode(array $data, UpdateShopShippingProfileRequestInterface $updateShopShippingProfileRequest): array
    {
        $value = $updateShopShippingProfileRequest->getOriginPostalCode();
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
    private static function applyProcessingTimeUnit(array $data, UpdateShopShippingProfileRequestInterface $updateShopShippingProfileRequest): array
    {
        $value = $updateShopShippingProfileRequest->getProcessingTimeUnit();
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
    private static function applyTitle(array $data, UpdateShopShippingProfileRequestInterface $updateShopShippingProfileRequest): array
    {
        $value = $updateShopShippingProfileRequest->getTitle();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_TITLE] = $value;

        return $data;
    }
}
