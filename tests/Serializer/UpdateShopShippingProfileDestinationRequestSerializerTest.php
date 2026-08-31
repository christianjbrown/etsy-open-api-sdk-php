<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileDestinationRequest;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileDestinationRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileDestinationRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateShopShippingProfileDestinationRequest::class)]
#[CoversClass(UpdateShopShippingProfileDestinationRequestSerializer::class)]
final class UpdateShopShippingProfileDestinationRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $destinationCountryIso = 'test-destinationCountryIso';
        $destinationRegion = 'test-destinationRegion';
        $mailClass = 'test-mailClass';
        $maxDeliveryDays = 1;
        $minDeliveryDays = 2;
        $primaryCost = 3.5;
        $secondaryCost = 4.5;
        $shippingCarrierId = 5;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [2, 'test-int-2'],
                    [5, 'test-int-5'],
                ]
            );
        $formValueEncoder->method('encodeFloat')
            ->willReturnMap(
                [
                    [3.5, 'test-float-3.5'],
                    [4.5, 'test-float-4.5'],
                ]
            );

        $updateShopShippingProfileDestinationRequest = (new UpdateShopShippingProfileDestinationRequest())
            ->setDestinationCountryIso($destinationCountryIso)
            ->setDestinationRegion($destinationRegion)
            ->setMailClass($mailClass)
            ->setMaxDeliveryDays($maxDeliveryDays)
            ->setMinDeliveryDays($minDeliveryDays)
            ->setPrimaryCost($primaryCost)
            ->setSecondaryCost($secondaryCost)
            ->setShippingCarrierId($shippingCarrierId);

        $serializer = new UpdateShopShippingProfileDestinationRequestSerializer($formValueEncoder);

        $expected = [
            UpdateShopShippingProfileDestinationRequestSerializerInterface::KEY_DESTINATION_COUNTRY_ISO => $destinationCountryIso,
            UpdateShopShippingProfileDestinationRequestSerializerInterface::KEY_DESTINATION_REGION => $destinationRegion,
            UpdateShopShippingProfileDestinationRequestSerializerInterface::KEY_MAIL_CLASS => $mailClass,
            UpdateShopShippingProfileDestinationRequestSerializerInterface::KEY_MAX_DELIVERY_DAYS => 'test-int-1',
            UpdateShopShippingProfileDestinationRequestSerializerInterface::KEY_MIN_DELIVERY_DAYS => 'test-int-2',
            UpdateShopShippingProfileDestinationRequestSerializerInterface::KEY_PRIMARY_COST => 'test-float-3.5',
            UpdateShopShippingProfileDestinationRequestSerializerInterface::KEY_SECONDARY_COST => 'test-float-4.5',
            UpdateShopShippingProfileDestinationRequestSerializerInterface::KEY_SHIPPING_CARRIER_ID => 'test-int-5',
        ];

        self::assertSame($expected, $serializer->serialize($updateShopShippingProfileDestinationRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);

        $updateShopShippingProfileDestinationRequest = new UpdateShopShippingProfileDestinationRequest();

        $serializer = new UpdateShopShippingProfileDestinationRequestSerializer($formValueEncoder);

        self::assertSame([], $serializer->serialize($updateShopShippingProfileDestinationRequest));
    }
}
