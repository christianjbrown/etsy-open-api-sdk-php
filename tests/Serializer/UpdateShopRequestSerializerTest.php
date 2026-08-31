<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\UpdateShopRequest;
use ChristianBrown\Etsy\Serializer\UpdateShopRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UpdateShopRequest::class)]
#[CoversClass(UpdateShopRequestSerializer::class)]
final class UpdateShopRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $announcement = 'test-announcement';
        $digitalSaleMessage = 'test-digitalSaleMessage';
        $policyAdditional = 'test-policyAdditional';
        $saleMessage = 'test-saleMessage';
        $title = 'test-title';

        $updateShopRequest = (new UpdateShopRequest())
            ->setAnnouncement($announcement)
            ->setDigitalSaleMessage($digitalSaleMessage)
            ->setPolicyAdditional($policyAdditional)
            ->setSaleMessage($saleMessage)
            ->setTitle($title);

        $serializer = new UpdateShopRequestSerializer();

        $expected = [
            UpdateShopRequestSerializerInterface::KEY_ANNOUNCEMENT => $announcement,
            UpdateShopRequestSerializerInterface::KEY_DIGITAL_SALE_MESSAGE => $digitalSaleMessage,
            UpdateShopRequestSerializerInterface::KEY_POLICY_ADDITIONAL => $policyAdditional,
            UpdateShopRequestSerializerInterface::KEY_SALE_MESSAGE => $saleMessage,
            UpdateShopRequestSerializerInterface::KEY_TITLE => $title,
        ];

        self::assertSame($expected, $serializer->serialize($updateShopRequest));
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $updateShopRequest = new UpdateShopRequest();

        $serializer = new UpdateShopRequestSerializer();

        self::assertSame([], $serializer->serialize($updateShopRequest));
    }
}
