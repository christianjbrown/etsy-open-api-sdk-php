<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

interface ReceiptsApiInterface extends ApiInterface
{
    public const URL = 'https://openapi.etsy.com/v3/application/shops/%d/receipts';
}
