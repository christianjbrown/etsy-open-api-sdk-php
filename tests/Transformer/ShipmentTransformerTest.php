<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\Shipment;
use ChristianBrown\Etsy\Transformer\ShipmentTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Shipment::class)]
#[CoversClass(ShipmentTransformer::class)]
final class ShipmentTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            ShipmentTransformerInterface::KEY_CARRIER_NAME => 'usps',
            ShipmentTransformerInterface::KEY_RECEIPT_SHIPPING_ID => 42,
            ShipmentTransformerInterface::KEY_SHIPMENT_NOTIFICATION_TIMESTAMP => 1600000000,
            ShipmentTransformerInterface::KEY_TRACKING_CODE => 'TRACK123',
        ];

        $transformer = new ShipmentTransformer();

        $actual = $transformer->transform($data);

        self::assertSame('usps', $actual->getCarrierName());
        self::assertSame(42, $actual->getReceiptShippingId());
        self::assertSame(1600000000, $actual->getShipmentNotificationTimestamp());
        self::assertSame('TRACK123', $actual->getTrackingCode());
    }

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, ?string $expectedCarrierName, ?int $expectedReceiptShippingId, ?int $expectedShipmentNotificationTimestamp, ?string $expectedTrackingCode): void
    {
        $transformer = new ShipmentTransformer();

        $actual = $transformer->transform($data);

        self::assertSame($expectedCarrierName, $actual->getCarrierName());
        self::assertSame($expectedReceiptShippingId, $actual->getReceiptShippingId());
        self::assertSame($expectedShipmentNotificationTimestamp, $actual->getShipmentNotificationTimestamp());
        self::assertSame($expectedTrackingCode, $actual->getTrackingCode());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, ?string, ?int, ?int, ?string}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $carrierName = ShipmentTransformerInterface::KEY_CARRIER_NAME;
        $receiptShippingId = ShipmentTransformerInterface::KEY_RECEIPT_SHIPPING_ID;
        $shipmentNotificationTimestamp = ShipmentTransformerInterface::KEY_SHIPMENT_NOTIFICATION_TIMESTAMP;
        $trackingCode = ShipmentTransformerInterface::KEY_TRACKING_CODE;

        yield 'allAbsent' => [[], null, null, null, null];
        yield 'carrierNameWrongType' => [[$carrierName => 42], null, null, null, null];
        yield 'receiptShippingIdZero' => [[$receiptShippingId => 0], null, 0, null, null];
        yield 'receiptShippingIdWrongType' => [[$receiptShippingId => 'not-int'], null, null, null, null];
        yield 'shipmentNotificationTimestampZero' => [[$shipmentNotificationTimestamp => 0], null, null, 0, null];
        yield 'shipmentNotificationTimestampWrongType' => [[$shipmentNotificationTimestamp => 'not-int'], null, null, null, null];
        yield 'trackingCodeWrongType' => [[$trackingCode => 42], null, null, null, null];
    }
}
