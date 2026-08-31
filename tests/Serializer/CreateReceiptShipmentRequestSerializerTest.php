<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\CreateReceiptShipmentRequest;
use ChristianBrown\Etsy\Model\ReceiptShipmentCustomsItemRequestInterface;
use ChristianBrown\Etsy\Serializer\CreateReceiptShipmentRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateReceiptShipmentRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\ReceiptShipmentCustomsItemRequestsSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CreateReceiptShipmentRequest::class)]
#[CoversClass(CreateReceiptShipmentRequestSerializer::class)]
final class CreateReceiptShipmentRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $carrierName = 'test-carrierName';
        $customsData = [self::createStub(ReceiptShipmentCustomsItemRequestInterface::class)];
        $dimensionUnits = 'test-dimensionUnits';
        $dutyAmount = 1.5;
        $dutyCurrency = 'test-dutyCurrency';
        $height = 2.5;
        $incoterm = 'test-incoterm';
        $length = 3.5;
        $mailClass = 'test-mailClass';
        $noteToBuyer = 'test-noteToBuyer';
        $revenueEligibility = 'test-revenueEligibility';
        $sendBcc = true;
        $shipDate = 'test-shipDate';
        $shipFromCountry = 'test-shipFromCountry';
        $shipToCountry = 'test-shipToCountry';
        $shippingLabelCost = 4.5;
        $shippingLabelCurrency = 'test-shippingLabelCurrency';
        $trackingCode = 'test-trackingCode';
        $weight = 5.5;
        $weightUnits = 'test-weightUnits';
        $width = 6.5;

        $receiptShipmentCustomsItemRequestsSerializer = self::createStub(ReceiptShipmentCustomsItemRequestsSerializerInterface::class);
        $receiptShipmentCustomsItemRequestsSerializer->method('serialize')
            ->willReturnMap(
                [
                    [$customsData, [['test-customsData']]],
                ]
            );

        $createReceiptShipmentRequest = (new CreateReceiptShipmentRequest())
            ->setCarrierName($carrierName)
            ->setCustomsData($customsData)
            ->setDimensionUnits($dimensionUnits)
            ->setDutyAmount($dutyAmount)
            ->setDutyCurrency($dutyCurrency)
            ->setHeight($height)
            ->setIncoterm($incoterm)
            ->setLength($length)
            ->setMailClass($mailClass)
            ->setNoteToBuyer($noteToBuyer)
            ->setRevenueEligibility($revenueEligibility)
            ->setSendBcc($sendBcc)
            ->setShipDate($shipDate)
            ->setShipFromCountry($shipFromCountry)
            ->setShipToCountry($shipToCountry)
            ->setShippingLabelCost($shippingLabelCost)
            ->setShippingLabelCurrency($shippingLabelCurrency)
            ->setTrackingCode($trackingCode)
            ->setWeight($weight)
            ->setWeightUnits($weightUnits)
            ->setWidth($width);

        $serializer = new CreateReceiptShipmentRequestSerializer($receiptShipmentCustomsItemRequestsSerializer);

        $expected = [
            CreateReceiptShipmentRequestSerializerInterface::KEY_CARRIER_NAME => $carrierName,
            CreateReceiptShipmentRequestSerializerInterface::KEY_CUSTOMS_DATA => [['test-customsData']],
            CreateReceiptShipmentRequestSerializerInterface::KEY_DIMENSION_UNITS => $dimensionUnits,
            CreateReceiptShipmentRequestSerializerInterface::KEY_DUTY_AMOUNT => $dutyAmount,
            CreateReceiptShipmentRequestSerializerInterface::KEY_DUTY_CURRENCY => $dutyCurrency,
            CreateReceiptShipmentRequestSerializerInterface::KEY_HEIGHT => $height,
            CreateReceiptShipmentRequestSerializerInterface::KEY_INCOTERM => $incoterm,
            CreateReceiptShipmentRequestSerializerInterface::KEY_LENGTH => $length,
            CreateReceiptShipmentRequestSerializerInterface::KEY_MAIL_CLASS => $mailClass,
            CreateReceiptShipmentRequestSerializerInterface::KEY_NOTE_TO_BUYER => $noteToBuyer,
            CreateReceiptShipmentRequestSerializerInterface::KEY_REVENUE_ELIGIBILITY => $revenueEligibility,
            CreateReceiptShipmentRequestSerializerInterface::KEY_SEND_BCC => $sendBcc,
            CreateReceiptShipmentRequestSerializerInterface::KEY_SHIP_DATE => $shipDate,
            CreateReceiptShipmentRequestSerializerInterface::KEY_SHIP_FROM_COUNTRY => $shipFromCountry,
            CreateReceiptShipmentRequestSerializerInterface::KEY_SHIPPING_LABEL_COST => $shippingLabelCost,
            CreateReceiptShipmentRequestSerializerInterface::KEY_SHIPPING_LABEL_CURRENCY => $shippingLabelCurrency,
            CreateReceiptShipmentRequestSerializerInterface::KEY_SHIP_TO_COUNTRY => $shipToCountry,
            CreateReceiptShipmentRequestSerializerInterface::KEY_TRACKING_CODE => $trackingCode,
            CreateReceiptShipmentRequestSerializerInterface::KEY_WEIGHT => $weight,
            CreateReceiptShipmentRequestSerializerInterface::KEY_WEIGHT_UNITS => $weightUnits,
            CreateReceiptShipmentRequestSerializerInterface::KEY_WIDTH => $width,
        ];

        self::assertSame($expected, $serializer->serialize($createReceiptShipmentRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $receiptShipmentCustomsItemRequestsSerializer = self::createStub(ReceiptShipmentCustomsItemRequestsSerializerInterface::class);

        $createReceiptShipmentRequest = new CreateReceiptShipmentRequest();

        $serializer = new CreateReceiptShipmentRequestSerializer($receiptShipmentCustomsItemRequestsSerializer);

        self::assertSame([], $serializer->serialize($createReceiptShipmentRequest));
    }
}
