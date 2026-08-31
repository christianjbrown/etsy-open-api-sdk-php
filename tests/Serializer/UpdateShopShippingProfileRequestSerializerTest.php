<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileRequest;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateShopShippingProfileRequest::class)]
#[CoversClass(UpdateShopShippingProfileRequestSerializer::class)]
final class UpdateShopShippingProfileRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $maxProcessingTime = 1;
        $minProcessingTime = 2;
        $originCountryIso = 'test-originCountryIso';
        $originPostalCode = 'test-originPostalCode';
        $processingTimeUnit = 'test-processingTimeUnit';
        $title = 'test-title';

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [2, 'test-int-2'],
                ]
            );

        $updateShopShippingProfileRequest = (new UpdateShopShippingProfileRequest())
            ->setMaxProcessingTime($maxProcessingTime)
            ->setMinProcessingTime($minProcessingTime)
            ->setOriginCountryIso($originCountryIso)
            ->setOriginPostalCode($originPostalCode)
            ->setProcessingTimeUnit($processingTimeUnit)
            ->setTitle($title);

        $serializer = new UpdateShopShippingProfileRequestSerializer($formValueEncoder);

        $expected = [
            UpdateShopShippingProfileRequestSerializerInterface::KEY_MAX_PROCESSING_TIME => 'test-int-1',
            UpdateShopShippingProfileRequestSerializerInterface::KEY_MIN_PROCESSING_TIME => 'test-int-2',
            UpdateShopShippingProfileRequestSerializerInterface::KEY_ORIGIN_COUNTRY_ISO => $originCountryIso,
            UpdateShopShippingProfileRequestSerializerInterface::KEY_ORIGIN_POSTAL_CODE => $originPostalCode,
            UpdateShopShippingProfileRequestSerializerInterface::KEY_PROCESSING_TIME_UNIT => $processingTimeUnit,
            UpdateShopShippingProfileRequestSerializerInterface::KEY_TITLE => $title,
        ];

        self::assertSame($expected, $serializer->serialize($updateShopShippingProfileRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);

        $updateShopShippingProfileRequest = new UpdateShopShippingProfileRequest();

        $serializer = new UpdateShopShippingProfileRequestSerializer($formValueEncoder);

        self::assertSame([], $serializer->serialize($updateShopShippingProfileRequest));
    }
}
