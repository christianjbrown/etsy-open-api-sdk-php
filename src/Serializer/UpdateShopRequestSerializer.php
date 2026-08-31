<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateShopRequestInterface;

final class UpdateShopRequestSerializer implements UpdateShopRequestSerializerInterface
{
    /**
     * @return array<string, string>
     */
    public function serialize(UpdateShopRequestInterface $updateShopRequest): array
    {
        $data = [];

        $data = self::applyAnnouncement($data, $updateShopRequest);
        $data = self::applyDigitalSaleMessage($data, $updateShopRequest);
        $data = self::applyPolicyAdditional($data, $updateShopRequest);
        $data = self::applySaleMessage($data, $updateShopRequest);
        $data = self::applyTitle($data, $updateShopRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyAnnouncement(array $data, UpdateShopRequestInterface $updateShopRequest): array
    {
        $value = $updateShopRequest->getAnnouncement();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ANNOUNCEMENT] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyDigitalSaleMessage(array $data, UpdateShopRequestInterface $updateShopRequest): array
    {
        $value = $updateShopRequest->getDigitalSaleMessage();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_DIGITAL_SALE_MESSAGE] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyPolicyAdditional(array $data, UpdateShopRequestInterface $updateShopRequest): array
    {
        $value = $updateShopRequest->getPolicyAdditional();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_POLICY_ADDITIONAL] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applySaleMessage(array $data, UpdateShopRequestInterface $updateShopRequest): array
    {
        $value = $updateShopRequest->getSaleMessage();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SALE_MESSAGE] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyTitle(array $data, UpdateShopRequestInterface $updateShopRequest): array
    {
        $value = $updateShopRequest->getTitle();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_TITLE] = $value;

        return $data;
    }
}
