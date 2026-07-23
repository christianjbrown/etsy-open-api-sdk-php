<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface PingInterface
{
    public function getApplicationId(): int;

    public function setApplicationId(int $value): self;
}
