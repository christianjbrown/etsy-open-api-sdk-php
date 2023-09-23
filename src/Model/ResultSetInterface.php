<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ResultSetInterface
{
    public function getCount(): int;

    public function getResults(): array;

    public function getTotal(): int;
}
