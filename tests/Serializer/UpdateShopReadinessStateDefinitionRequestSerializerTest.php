<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateShopReadinessStateDefinitionRequest;
use ChristianBrown\Etsy\Serializer\UpdateShopReadinessStateDefinitionRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopReadinessStateDefinitionRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateShopReadinessStateDefinitionRequest::class)]
#[CoversClass(UpdateShopReadinessStateDefinitionRequestSerializer::class)]
final class UpdateShopReadinessStateDefinitionRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $maxProcessingTime = 1;
        $minProcessingTime = 2;
        $processingTimeUnit = 'test-processingTimeUnit';
        $readinessState = 'test-readinessState';

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [2, 'test-int-2'],
                ]
            );

        $updateShopReadinessStateDefinitionRequest = (new UpdateShopReadinessStateDefinitionRequest())
            ->setMaxProcessingTime($maxProcessingTime)
            ->setMinProcessingTime($minProcessingTime)
            ->setProcessingTimeUnit($processingTimeUnit)
            ->setReadinessState($readinessState);

        $serializer = new UpdateShopReadinessStateDefinitionRequestSerializer($formValueEncoder);

        $expected = [
            UpdateShopReadinessStateDefinitionRequestSerializerInterface::KEY_MAX_PROCESSING_TIME => 'test-int-1',
            UpdateShopReadinessStateDefinitionRequestSerializerInterface::KEY_MIN_PROCESSING_TIME => 'test-int-2',
            UpdateShopReadinessStateDefinitionRequestSerializerInterface::KEY_PROCESSING_TIME_UNIT => $processingTimeUnit,
            UpdateShopReadinessStateDefinitionRequestSerializerInterface::KEY_READINESS_STATE => $readinessState,
        ];

        self::assertSame($expected, $serializer->serialize($updateShopReadinessStateDefinitionRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);

        $updateShopReadinessStateDefinitionRequest = new UpdateShopReadinessStateDefinitionRequest();

        $serializer = new UpdateShopReadinessStateDefinitionRequestSerializer($formValueEncoder);

        self::assertSame([], $serializer->serialize($updateShopReadinessStateDefinitionRequest));
    }
}
