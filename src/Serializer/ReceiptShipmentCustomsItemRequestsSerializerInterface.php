<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ReceiptShipmentCustomsItemRequestInterface;

interface ReceiptShipmentCustomsItemRequestsSerializerInterface
{
    /**
     * @param array<int, ReceiptShipmentCustomsItemRequestInterface> $receiptShipmentCustomsItemRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $receiptShipmentCustomsItemRequests): array;
}
