<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileRequest;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CreateShopShippingProfileRequest::class)]
#[CoversClass(CreateShopShippingProfileRequestSerializer::class)]
final class CreateShopShippingProfileRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $title = 'test-title';
        $originCountryIso = 'test-originCountryIso';
        $primaryCost = 1.5;
        $secondaryCost = 2.5;
        $destinationCountryIso = 'test-destinationCountryIso';
        $destinationRegion = 'test-destinationRegion';
        $mailClass = 'test-mailClass';
        $maxDeliveryDays = 3;
        $maxProcessingTime = 4;
        $minDeliveryDays = 5;
        $minProcessingTime = 6;
        $originPostalCode = 'test-originPostalCode';
        $processingTimeUnit = 'test-processingTimeUnit';
        $shippingCarrierId = 7;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [3, 'test-int-3'],
                    [4, 'test-int-4'],
                    [5, 'test-int-5'],
                    [6, 'test-int-6'],
                    [7, 'test-int-7'],
                ]
            );
        $formValueEncoder->method('encodeFloat')
            ->willReturnMap(
                [
                    [1.5, 'test-float-1.5'],
                    [2.5, 'test-float-2.5'],
                ]
            );

        $createShopShippingProfileRequest = (new CreateShopShippingProfileRequest($title, $originCountryIso, $primaryCost, $secondaryCost))
            ->setTitle($title)
            ->setOriginCountryIso($originCountryIso)
            ->setPrimaryCost($primaryCost)
            ->setSecondaryCost($secondaryCost)
            ->setDestinationCountryIso($destinationCountryIso)
            ->setDestinationRegion($destinationRegion)
            ->setMailClass($mailClass)
            ->setMaxDeliveryDays($maxDeliveryDays)
            ->setMaxProcessingTime($maxProcessingTime)
            ->setMinDeliveryDays($minDeliveryDays)
            ->setMinProcessingTime($minProcessingTime)
            ->setOriginPostalCode($originPostalCode)
            ->setProcessingTimeUnit($processingTimeUnit)
            ->setShippingCarrierId($shippingCarrierId);

        $serializer = new CreateShopShippingProfileRequestSerializer($formValueEncoder);

        $expected = [
            CreateShopShippingProfileRequestSerializerInterface::KEY_DESTINATION_COUNTRY_ISO => $destinationCountryIso,
            CreateShopShippingProfileRequestSerializerInterface::KEY_DESTINATION_REGION => $destinationRegion,
            CreateShopShippingProfileRequestSerializerInterface::KEY_MAIL_CLASS => $mailClass,
            CreateShopShippingProfileRequestSerializerInterface::KEY_MAX_DELIVERY_DAYS => 'test-int-3',
            CreateShopShippingProfileRequestSerializerInterface::KEY_MAX_PROCESSING_TIME => 'test-int-4',
            CreateShopShippingProfileRequestSerializerInterface::KEY_MIN_DELIVERY_DAYS => 'test-int-5',
            CreateShopShippingProfileRequestSerializerInterface::KEY_MIN_PROCESSING_TIME => 'test-int-6',
            CreateShopShippingProfileRequestSerializerInterface::KEY_ORIGIN_COUNTRY_ISO => $originCountryIso,
            CreateShopShippingProfileRequestSerializerInterface::KEY_ORIGIN_POSTAL_CODE => $originPostalCode,
            CreateShopShippingProfileRequestSerializerInterface::KEY_PRIMARY_COST => 'test-float-1.5',
            CreateShopShippingProfileRequestSerializerInterface::KEY_PROCESSING_TIME_UNIT => $processingTimeUnit,
            CreateShopShippingProfileRequestSerializerInterface::KEY_SECONDARY_COST => 'test-float-2.5',
            CreateShopShippingProfileRequestSerializerInterface::KEY_SHIPPING_CARRIER_ID => 'test-int-7',
            CreateShopShippingProfileRequestSerializerInterface::KEY_TITLE => $title,
        ];

        self::assertSame($expected, $serializer->serialize($createShopShippingProfileRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $title = 'test-title';
        $originCountryIso = 'test-originCountryIso';
        $primaryCost = 1.5;
        $secondaryCost = 2.5;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeFloat')
            ->willReturnMap(
                [
                    [1.5, 'test-float-1.5'],
                    [2.5, 'test-float-2.5'],
                ]
            );

        $createShopShippingProfileRequest = new CreateShopShippingProfileRequest($title, $originCountryIso, $primaryCost, $secondaryCost);

        $serializer = new CreateShopShippingProfileRequestSerializer($formValueEncoder);

        $expected = [
            CreateShopShippingProfileRequestSerializerInterface::KEY_ORIGIN_COUNTRY_ISO => $originCountryIso,
            CreateShopShippingProfileRequestSerializerInterface::KEY_PRIMARY_COST => 'test-float-1.5',
            CreateShopShippingProfileRequestSerializerInterface::KEY_SECONDARY_COST => 'test-float-2.5',
            CreateShopShippingProfileRequestSerializerInterface::KEY_TITLE => $title,
        ];

        self::assertSame($expected, $serializer->serialize($createShopShippingProfileRequest));
    }
}
