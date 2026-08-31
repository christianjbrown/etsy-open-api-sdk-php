<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileUpgradeRequest;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileUpgradeRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileUpgradeRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateShopShippingProfileUpgradeRequest::class)]
#[CoversClass(UpdateShopShippingProfileUpgradeRequestSerializer::class)]
final class UpdateShopShippingProfileUpgradeRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $mailClass = 'test-mailClass';
        $maxDeliveryDays = 1;
        $minDeliveryDays = 2;
        $price = 3.5;
        $secondaryPrice = 4.5;
        $shippingCarrierId = 5;
        $type = 6;
        $upgradeName = 'test-upgradeName';

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [2, 'test-int-2'],
                    [5, 'test-int-5'],
                    [6, 'test-int-6'],
                ]
            );
        $formValueEncoder->method('encodeFloat')
            ->willReturnMap(
                [
                    [3.5, 'test-float-3.5'],
                    [4.5, 'test-float-4.5'],
                ]
            );

        $updateShopShippingProfileUpgradeRequest = (new UpdateShopShippingProfileUpgradeRequest())
            ->setMailClass($mailClass)
            ->setMaxDeliveryDays($maxDeliveryDays)
            ->setMinDeliveryDays($minDeliveryDays)
            ->setPrice($price)
            ->setSecondaryPrice($secondaryPrice)
            ->setShippingCarrierId($shippingCarrierId)
            ->setType($type)
            ->setUpgradeName($upgradeName);

        $serializer = new UpdateShopShippingProfileUpgradeRequestSerializer($formValueEncoder);

        $expected = [
            UpdateShopShippingProfileUpgradeRequestSerializerInterface::KEY_MAIL_CLASS => $mailClass,
            UpdateShopShippingProfileUpgradeRequestSerializerInterface::KEY_MAX_DELIVERY_DAYS => 'test-int-1',
            UpdateShopShippingProfileUpgradeRequestSerializerInterface::KEY_MIN_DELIVERY_DAYS => 'test-int-2',
            UpdateShopShippingProfileUpgradeRequestSerializerInterface::KEY_PRICE => 'test-float-3.5',
            UpdateShopShippingProfileUpgradeRequestSerializerInterface::KEY_SECONDARY_PRICE => 'test-float-4.5',
            UpdateShopShippingProfileUpgradeRequestSerializerInterface::KEY_SHIPPING_CARRIER_ID => 'test-int-5',
            UpdateShopShippingProfileUpgradeRequestSerializerInterface::KEY_TYPE => 'test-int-6',
            UpdateShopShippingProfileUpgradeRequestSerializerInterface::KEY_UPGRADE_NAME => $upgradeName,
        ];

        self::assertSame($expected, $serializer->serialize($updateShopShippingProfileUpgradeRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);

        $updateShopShippingProfileUpgradeRequest = new UpdateShopShippingProfileUpgradeRequest();

        $serializer = new UpdateShopShippingProfileUpgradeRequestSerializer($formValueEncoder);

        self::assertSame([], $serializer->serialize($updateShopShippingProfileUpgradeRequest));
    }
}
