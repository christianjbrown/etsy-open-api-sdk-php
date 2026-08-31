<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ReceiptShipmentCustomsItemRequestInterface;

final class ReceiptShipmentCustomsItemRequestSerializer implements ReceiptShipmentCustomsItemRequestSerializerInterface
{
    /**
     * @return array<string, mixed>
     */
    public function serialize(ReceiptShipmentCustomsItemRequestInterface $receiptShipmentCustomsItemRequest): array
    {
        $data = [];

        $data = self::applyCountryOfOrigin($data, $receiptShipmentCustomsItemRequest);
        $data = self::applyDeclaredValue($data, $receiptShipmentCustomsItemRequest);
        $data = self::applyHsCode($data, $receiptShipmentCustomsItemRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyCountryOfOrigin(array $data, ReceiptShipmentCustomsItemRequestInterface $receiptShipmentCustomsItemRequest): array
    {
        $data[self::KEY_COUNTRY_OF_ORIGIN] = $receiptShipmentCustomsItemRequest->getCountryOfOrigin();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyDeclaredValue(array $data, ReceiptShipmentCustomsItemRequestInterface $receiptShipmentCustomsItemRequest): array
    {
        $data[self::KEY_DECLARED_VALUE] = $receiptShipmentCustomsItemRequest->getDeclaredValue();

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyHsCode(array $data, ReceiptShipmentCustomsItemRequestInterface $receiptShipmentCustomsItemRequest): array
    {
        $data[self::KEY_H_S_CODE] = $receiptShipmentCustomsItemRequest->getHsCode();

        return $data;
    }
}
