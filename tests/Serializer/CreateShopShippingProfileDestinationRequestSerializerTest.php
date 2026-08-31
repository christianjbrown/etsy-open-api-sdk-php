<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileDestinationRequest;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileDestinationRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileDestinationRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CreateShopShippingProfileDestinationRequest::class)]
#[CoversClass(CreateShopShippingProfileDestinationRequestSerializer::class)]
final class CreateShopShippingProfileDestinationRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $primaryCost = 1.5;
        $secondaryCost = 2.5;
        $destinationCountryIso = 'test-destinationCountryIso';
        $destinationRegion = 'test-destinationRegion';
        $mailClass = 'test-mailClass';
        $maxDeliveryDays = 3;
        $minDeliveryDays = 4;
        $shippingCarrierId = 5;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [3, 'test-int-3'],
                    [4, 'test-int-4'],
                    [5, 'test-int-5'],
                ]
            );
        $formValueEncoder->method('encodeFloat')
            ->willReturnMap(
                [
                    [1.5, 'test-float-1.5'],
                    [2.5, 'test-float-2.5'],
                ]
            );

        $createShopShippingProfileDestinationRequest = (new CreateShopShippingProfileDestinationRequest($primaryCost, $secondaryCost))
            ->setPrimaryCost($primaryCost)
            ->setSecondaryCost($secondaryCost)
            ->setDestinationCountryIso($destinationCountryIso)
            ->setDestinationRegion($destinationRegion)
            ->setMailClass($mailClass)
            ->setMaxDeliveryDays($maxDeliveryDays)
            ->setMinDeliveryDays($minDeliveryDays)
            ->setShippingCarrierId($shippingCarrierId);

        $serializer = new CreateShopShippingProfileDestinationRequestSerializer($formValueEncoder);

        $expected = [
            CreateShopShippingProfileDestinationRequestSerializerInterface::KEY_DESTINATION_COUNTRY_ISO => $destinationCountryIso,
            CreateShopShippingProfileDestinationRequestSerializerInterface::KEY_DESTINATION_REGION => $destinationRegion,
            CreateShopShippingProfileDestinationRequestSerializerInterface::KEY_MAIL_CLASS => $mailClass,
            CreateShopShippingProfileDestinationRequestSerializerInterface::KEY_MAX_DELIVERY_DAYS => 'test-int-3',
            CreateShopShippingProfileDestinationRequestSerializerInterface::KEY_MIN_DELIVERY_DAYS => 'test-int-4',
            CreateShopShippingProfileDestinationRequestSerializerInterface::KEY_PRIMARY_COST => 'test-float-1.5',
            CreateShopShippingProfileDestinationRequestSerializerInterface::KEY_SECONDARY_COST => 'test-float-2.5',
            CreateShopShippingProfileDestinationRequestSerializerInterface::KEY_SHIPPING_CARRIER_ID => 'test-int-5',
        ];

        self::assertSame($expected, $serializer->serialize($createShopShippingProfileDestinationRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
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

        $createShopShippingProfileDestinationRequest = new CreateShopShippingProfileDestinationRequest($primaryCost, $secondaryCost);

        $serializer = new CreateShopShippingProfileDestinationRequestSerializer($formValueEncoder);

        $expected = [
            CreateShopShippingProfileDestinationRequestSerializerInterface::KEY_PRIMARY_COST => 'test-float-1.5',
            CreateShopShippingProfileDestinationRequestSerializerInterface::KEY_SECONDARY_COST => 'test-float-2.5',
        ];

        self::assertSame($expected, $serializer->serialize($createShopShippingProfileDestinationRequest));
    }
}
