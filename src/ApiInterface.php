<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy;

use ChristianBrown\Etsy\Endpoint\ReceiptsApiInterface;

interface ApiInterface
{
    public function getReceiptsApi(): ReceiptsApiInterface;
}
