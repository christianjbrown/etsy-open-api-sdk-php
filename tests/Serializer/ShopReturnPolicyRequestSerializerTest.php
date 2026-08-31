<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\ShopReturnPolicyRequest;
use ChristianBrown\Etsy\Serializer\ShopReturnPolicyRequestSerializer;
use ChristianBrown\Etsy\Serializer\ShopReturnPolicyRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ShopReturnPolicyRequest::class)]
#[CoversClass(ShopReturnPolicyRequestSerializer::class)]
final class ShopReturnPolicyRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $acceptsReturns = true;
        $acceptsExchanges = true;
        $returnDeadline = 1;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                ]
            );
        $formValueEncoder->method('encodeBool')
            ->willReturnMap(
                [
                    [true, 'test-bool-true'],
                    [false, 'test-bool-false'],
                ]
            );

        $shopReturnPolicyRequest = (new ShopReturnPolicyRequest($acceptsReturns, $acceptsExchanges))
            ->setAcceptsReturns($acceptsReturns)
            ->setAcceptsExchanges($acceptsExchanges)
            ->setReturnDeadline($returnDeadline);

        $serializer = new ShopReturnPolicyRequestSerializer($formValueEncoder);

        $expected = [
            ShopReturnPolicyRequestSerializerInterface::KEY_ACCEPTS_EXCHANGES => 'test-bool-true',
            ShopReturnPolicyRequestSerializerInterface::KEY_ACCEPTS_RETURNS => 'test-bool-true',
            ShopReturnPolicyRequestSerializerInterface::KEY_RETURN_DEADLINE => 'test-int-1',
        ];

        self::assertSame($expected, $serializer->serialize($shopReturnPolicyRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $acceptsReturns = true;
        $acceptsExchanges = true;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeBool')
            ->willReturnMap(
                [
                    [true, 'test-bool-true'],
                    [false, 'test-bool-false'],
                ]
            );

        $shopReturnPolicyRequest = new ShopReturnPolicyRequest($acceptsReturns, $acceptsExchanges);

        $serializer = new ShopReturnPolicyRequestSerializer($formValueEncoder);

        $expected = [
            ShopReturnPolicyRequestSerializerInterface::KEY_ACCEPTS_EXCHANGES => 'test-bool-true',
            ShopReturnPolicyRequestSerializerInterface::KEY_ACCEPTS_RETURNS => 'test-bool-true',
        ];

        self::assertSame($expected, $serializer->serialize($shopReturnPolicyRequest));
    }
}
