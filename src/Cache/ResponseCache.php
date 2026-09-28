<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Cache;

final class ResponseCache implements ResponseCacheInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $values = [];

    public function clear(): void
    {
        $this->values = [];
    }

    public function delete(string $key): void
    {
        unset($this->values[$key]);
    }

    public function get(string $key): mixed
    {
        return $this->values[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->values[$key]);
    }

    public function set(string $key, mixed $value): void
    {
        $this->values[$key] = $value;
    }
}
