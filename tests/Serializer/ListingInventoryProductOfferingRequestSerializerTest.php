<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingRequest;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductOfferingRequestSerializer;
use ChristianBrown\Etsy\Serializer\ListingInventoryProductOfferingRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingInventoryProductOfferingRequest::class)]
#[CoversClass(ListingInventoryProductOfferingRequestSerializer::class)]
final class ListingInventoryProductOfferingRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $price = 1.5;
        $quantity = 2;
        $isEnabled = true;
        $readinessStateId = 3;

        $listingInventoryProductOfferingRequest = (new ListingInventoryProductOfferingRequest($price, $quantity, $isEnabled, $readinessStateId))
            ->setPrice($price)
            ->setQuantity($quantity)
            ->setIsEnabled($isEnabled)
            ->setReadinessStateId($readinessStateId);

        $serializer = new ListingInventoryProductOfferingRequestSerializer();

        $expected = [
            ListingInventoryProductOfferingRequestSerializerInterface::KEY_IS_ENABLED => $isEnabled,
            ListingInventoryProductOfferingRequestSerializerInterface::KEY_PRICE => $price,
            ListingInventoryProductOfferingRequestSerializerInterface::KEY_QUANTITY => $quantity,
            ListingInventoryProductOfferingRequestSerializerInterface::KEY_READINESS_STATE_ID => $readinessStateId,
        ];

        self::assertSame($expected, $serializer->serialize($listingInventoryProductOfferingRequest));
    }
}
