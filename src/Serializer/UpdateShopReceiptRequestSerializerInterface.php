<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateShopReceiptRequestInterface;

interface UpdateShopReceiptRequestSerializerInterface
{
    public const string KEY_WAS_PAID = 'was_paid';
    public const string KEY_WAS_SHIPPED = 'was_shipped';

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateShopReceiptRequestInterface $updateShopReceiptRequest): array;
}
