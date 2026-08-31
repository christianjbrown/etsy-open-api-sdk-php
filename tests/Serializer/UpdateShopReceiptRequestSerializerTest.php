<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateShopReceiptRequest;
use ChristianBrown\Etsy\Serializer\UpdateShopReceiptRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopReceiptRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateShopReceiptRequest::class)]
#[CoversClass(UpdateShopReceiptRequestSerializer::class)]
final class UpdateShopReceiptRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $wasPaid = true;
        $wasShipped = true;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeBool')
            ->willReturnMap(
                [
                    [true, 'test-bool-true'],
                    [false, 'test-bool-false'],
                ]
            );

        $updateShopReceiptRequest = (new UpdateShopReceiptRequest())
            ->setWasPaid($wasPaid)
            ->setWasShipped($wasShipped);

        $serializer = new UpdateShopReceiptRequestSerializer($formValueEncoder);

        $expected = [
            UpdateShopReceiptRequestSerializerInterface::KEY_WAS_PAID => 'test-bool-true',
            UpdateShopReceiptRequestSerializerInterface::KEY_WAS_SHIPPED => 'test-bool-true',
        ];

        self::assertSame($expected, $serializer->serialize($updateShopReceiptRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);

        $updateShopReceiptRequest = new UpdateShopReceiptRequest();

        $serializer = new UpdateShopReceiptRequestSerializer($formValueEncoder);

        self::assertSame([], $serializer->serialize($updateShopReceiptRequest));
    }
}
