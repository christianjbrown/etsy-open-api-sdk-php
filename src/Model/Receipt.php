<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Receipt implements ModelInterface
{
    public array $transactions;

    // @todo Lots more fields to transform if we need them..
}
