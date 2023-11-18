<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Transaction implements TransactionInterface
{
    public int $listingId;
    public int $quantity;

    // @todo Lots more fields to transform if we need them..
}
