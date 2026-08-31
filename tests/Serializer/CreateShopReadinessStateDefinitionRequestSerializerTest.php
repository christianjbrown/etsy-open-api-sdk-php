<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\CreateShopReadinessStateDefinitionRequest;
use ChristianBrown\Etsy\Serializer\CreateShopReadinessStateDefinitionRequestSerializer;
use ChristianBrown\Etsy\Serializer\CreateShopReadinessStateDefinitionRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CreateShopReadinessStateDefinitionRequest::class)]
#[CoversClass(CreateShopReadinessStateDefinitionRequestSerializer::class)]
final class CreateShopReadinessStateDefinitionRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $readinessState = 'test-readinessState';
        $minProcessingTime = 1;
        $maxProcessingTime = 2;
        $processingTimeUnit = 'test-processingTimeUnit';

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [2, 'test-int-2'],
                ]
            );

        $createShopReadinessStateDefinitionRequest = (new CreateShopReadinessStateDefinitionRequest($readinessState, $minProcessingTime, $maxProcessingTime))
            ->setReadinessState($readinessState)
            ->setMinProcessingTime($minProcessingTime)
            ->setMaxProcessingTime($maxProcessingTime)
            ->setProcessingTimeUnit($processingTimeUnit);

        $serializer = new CreateShopReadinessStateDefinitionRequestSerializer($formValueEncoder);

        $expected = [
            CreateShopReadinessStateDefinitionRequestSerializerInterface::KEY_MAX_PROCESSING_TIME => 'test-int-2',
            CreateShopReadinessStateDefinitionRequestSerializerInterface::KEY_MIN_PROCESSING_TIME => 'test-int-1',
            CreateShopReadinessStateDefinitionRequestSerializerInterface::KEY_PROCESSING_TIME_UNIT => $processingTimeUnit,
            CreateShopReadinessStateDefinitionRequestSerializerInterface::KEY_READINESS_STATE => $readinessState,
        ];

        self::assertSame($expected, $serializer->serialize($createShopReadinessStateDefinitionRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $readinessState = 'test-readinessState';
        $minProcessingTime = 1;
        $maxProcessingTime = 2;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [2, 'test-int-2'],
                ]
            );

        $createShopReadinessStateDefinitionRequest = new CreateShopReadinessStateDefinitionRequest($readinessState, $minProcessingTime, $maxProcessingTime);

        $serializer = new CreateShopReadinessStateDefinitionRequestSerializer($formValueEncoder);

        $expected = [
            CreateShopReadinessStateDefinitionRequestSerializerInterface::KEY_MAX_PROCESSING_TIME => 'test-int-2',
            CreateShopReadinessStateDefinitionRequestSerializerInterface::KEY_MIN_PROCESSING_TIME => 'test-int-1',
            CreateShopReadinessStateDefinitionRequestSerializerInterface::KEY_READINESS_STATE => $readinessState,
        ];

        self::assertSame($expected, $serializer->serialize($createShopReadinessStateDefinitionRequest));
    }
}
