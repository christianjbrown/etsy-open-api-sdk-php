<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ReceiptShipmentCustomsItemRequest;
use ChristianBrown\Etsy\Serializer\ReceiptShipmentCustomsItemRequestSerializer;
use ChristianBrown\Etsy\Serializer\ReceiptShipmentCustomsItemRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReceiptShipmentCustomsItemRequest::class)]
#[CoversClass(ReceiptShipmentCustomsItemRequestSerializer::class)]
final class ReceiptShipmentCustomsItemRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $countryOfOrigin = 'test-countryOfOrigin';
        $declaredValue = 1.5;
        $hsCode = 'test-hsCode';

        $receiptShipmentCustomsItemRequest = (new ReceiptShipmentCustomsItemRequest($countryOfOrigin, $declaredValue, $hsCode))
            ->setCountryOfOrigin($countryOfOrigin)
            ->setDeclaredValue($declaredValue)
            ->setHsCode($hsCode);

        $serializer = new ReceiptShipmentCustomsItemRequestSerializer();

        $expected = [
            ReceiptShipmentCustomsItemRequestSerializerInterface::KEY_COUNTRY_OF_ORIGIN => $countryOfOrigin,
            ReceiptShipmentCustomsItemRequestSerializerInterface::KEY_DECLARED_VALUE => $declaredValue,
            ReceiptShipmentCustomsItemRequestSerializerInterface::KEY_H_S_CODE => $hsCode,
        ];

        self::assertSame($expected, $serializer->serialize($receiptShipmentCustomsItemRequest));
    }
}
