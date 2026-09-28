<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Role;

use ChristianBrown\Etsy\Api\ShopReceiptApiInterface;
use ChristianBrown\Etsy\Api\ShopReceiptTransactionApiInterface;

/**
 * Orders: shop receipts and their transactions.
 */
interface EtsyReceiptsAwareInterface
{
    public function getShopReceiptApi(): ShopReceiptApiInterface;

    public function getShopReceiptTransactionApi(): ShopReceiptTransactionApiInterface;
}
