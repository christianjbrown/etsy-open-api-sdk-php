<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ReceiptShipmentCustomsItemRequestInterface;

use function array_values;
use function count;

final class ReceiptShipmentCustomsItemRequestsSerializer implements ReceiptShipmentCustomsItemRequestsSerializerInterface
{
    private ReceiptShipmentCustomsItemRequestSerializerInterface $receiptShipmentCustomsItemRequestSerializer;

    public function __construct(ReceiptShipmentCustomsItemRequestSerializerInterface $receiptShipmentCustomsItemRequestSerializer)
    {
        $this->receiptShipmentCustomsItemRequestSerializer = $receiptShipmentCustomsItemRequestSerializer;
    }

    /**
     * @param array<int, ReceiptShipmentCustomsItemRequestInterface> $receiptShipmentCustomsItemRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $receiptShipmentCustomsItemRequests): array
    {
        $data = [];
        $values = array_values($receiptShipmentCustomsItemRequests);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $data[] = $this->receiptShipmentCustomsItemRequestSerializer->serialize($values[$i]);
        }

        return $data;
    }
}
