<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ReceiptShipmentCustomsItemRequestInterface;

interface ReceiptShipmentCustomsItemRequestSerializerInterface
{
    public const string KEY_COUNTRY_OF_ORIGIN = 'country_of_origin';
    public const string KEY_DECLARED_VALUE = 'declared_value';
    public const string KEY_H_S_CODE = 'HS_code';

    /**
     * @return array<string, mixed>
     */
    public function serialize(ReceiptShipmentCustomsItemRequestInterface $receiptShipmentCustomsItemRequest): array;
}
