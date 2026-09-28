<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Role;

use ChristianBrown\Etsy\Api\LedgerEntryApiInterface;
use ChristianBrown\Etsy\Api\PaymentApiInterface;

/**
 * Payments and the shop's payment account ledger.
 */
interface EtsyPaymentsAwareInterface
{
    public function getLedgerEntryApi(): LedgerEntryApiInterface;

    public function getPaymentApi(): PaymentApiInterface;
}
