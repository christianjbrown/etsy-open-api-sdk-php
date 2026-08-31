<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateListingPropertyRequest;
use ChristianBrown\Etsy\Serializer\UpdateListingPropertyRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateListingPropertyRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateListingPropertyRequest::class)]
#[CoversClass(UpdateListingPropertyRequestSerializer::class)]
final class UpdateListingPropertyRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $valueIds = [1, 2];
        $values = ['test-values-1', 'test-values-2'];
        $scaleId = 3;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [3, 'test-int-3'],
                ]
            );
        $formValueEncoder->method('encodeStringList')
            ->willReturnMap(
                [
                    [UpdateListingPropertyRequestSerializerInterface::KEY_VALUES, $values, ['test-values' => 'test-values-value']],
                ]
            );
        $formValueEncoder->method('encodeIntList')
            ->willReturnMap(
                [
                    [UpdateListingPropertyRequestSerializerInterface::KEY_VALUE_IDS, $valueIds, ['test-valueIds' => 'test-valueIds-value']],
                ]
            );

        $updateListingPropertyRequest = (new UpdateListingPropertyRequest($valueIds, $values))
            ->setValueIds($valueIds)
            ->setValues($values)
            ->setScaleId($scaleId);

        $serializer = new UpdateListingPropertyRequestSerializer($formValueEncoder);

        $expected = [
            UpdateListingPropertyRequestSerializerInterface::KEY_SCALE_ID => 'test-int-3',
            'test-valueIds' => 'test-valueIds-value',
            'test-values' => 'test-values-value',
        ];

        self::assertSame($expected, $serializer->serialize($updateListingPropertyRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $valueIds = [1, 2];
        $values = ['test-values-1', 'test-values-2'];

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeStringList')
            ->willReturnMap(
                [
                    [UpdateListingPropertyRequestSerializerInterface::KEY_VALUES, $values, ['test-values' => 'test-values-value']],
                ]
            );
        $formValueEncoder->method('encodeIntList')
            ->willReturnMap(
                [
                    [UpdateListingPropertyRequestSerializerInterface::KEY_VALUE_IDS, $valueIds, ['test-valueIds' => 'test-valueIds-value']],
                ]
            );

        $updateListingPropertyRequest = new UpdateListingPropertyRequest($valueIds, $values);

        $serializer = new UpdateListingPropertyRequestSerializer($formValueEncoder);

        $expected = [
            'test-valueIds' => 'test-valueIds-value',
            'test-values' => 'test-values-value',
        ];

        self::assertSame($expected, $serializer->serialize($updateListingPropertyRequest));
    }
}
