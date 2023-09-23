<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ResultSet implements ResultSetInterface
{
    private array $results;
    private int $total;

    public function __construct(int $total, array $results)
    {
        $this->total = $total;
        $this->results = $results;
    }

    public function getCount(): int
    {
        return count($this->results);
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
