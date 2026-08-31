<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileUpgradeRequest;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileUpgradeRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileUpgradeRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CreateShopShippingProfileUpgradeRequest::class)]
#[CoversClass(CreateShopShippingProfileUpgradeRequestSerializer::class)]
final class CreateShopShippingProfileUpgradeRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $type = 1;
        $upgradeName = 'test-upgradeName';
        $price = 2.5;
        $secondaryPrice = 3.5;
        $mailClass = 'test-mailClass';
        $maxDeliveryDays = 4;
        $minDeliveryDays = 5;
        $shippingCarrierId = 6;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [4, 'test-int-4'],
                    [5, 'test-int-5'],
                    [6, 'test-int-6'],
                ]
            );
        $formValueEncoder->method('encodeFloat')
            ->willReturnMap(
                [
                    [2.5, 'test-float-2.5'],
                    [3.5, 'test-float-3.5'],
                ]
            );

        $createShopShippingProfileUpgradeRequest = (new CreateShopShippingProfileUpgradeRequest($type, $upgradeName, $price, $secondaryPrice))
            ->setType($type)
            ->setUpgradeName($upgradeName)
            ->setPrice($price)
            ->setSecondaryPrice($secondaryPrice)
            ->setMailClass($mailClass)
            ->setMaxDeliveryDays($maxDeliveryDays)
            ->setMinDeliveryDays($minDeliveryDays)
            ->setShippingCarrierId($shippingCarrierId);

        $serializer = new CreateShopShippingProfileUpgradeRequestSerializer($formValueEncoder);

        $expected = [
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_MAIL_CLASS => $mailClass,
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_MAX_DELIVERY_DAYS => 'test-int-4',
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_MIN_DELIVERY_DAYS => 'test-int-5',
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_PRICE => 'test-float-2.5',
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_SECONDARY_PRICE => 'test-float-3.5',
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_SHIPPING_CARRIER_ID => 'test-int-6',
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_TYPE => 'test-int-1',
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_UPGRADE_NAME => $upgradeName,
        ];

        self::assertSame($expected, $serializer->serialize($createShopShippingProfileUpgradeRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $type = 1;
        $upgradeName = 'test-upgradeName';
        $price = 2.5;
        $secondaryPrice = 3.5;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                ]
            );
        $formValueEncoder->method('encodeFloat')
            ->willReturnMap(
                [
                    [2.5, 'test-float-2.5'],
                    [3.5, 'test-float-3.5'],
                ]
            );

        $createShopShippingProfileUpgradeRequest = new CreateShopShippingProfileUpgradeRequest($type, $upgradeName, $price, $secondaryPrice);

        $serializer = new CreateShopShippingProfileUpgradeRequestSerializer($formValueEncoder);

        $expected = [
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_PRICE => 'test-float-2.5',
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_SECONDARY_PRICE => 'test-float-3.5',
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_TYPE => 'test-int-1',
            CreateShopShippingProfileUpgradeRequestSerializerInterface::KEY_UPGRADE_NAME => $upgradeName,
        ];

        self::assertSame($expected, $serializer->serialize($createShopShippingProfileUpgradeRequest));
    }
}
