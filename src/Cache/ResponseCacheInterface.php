<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Cache;

/**
 * The in-memory response cache every `Api/` class used to keep as its own private array. One
 * instance is one cache: an `Api` client with more than one independently invalidated cache (a
 * list cache and a by-id cache, say) is given one `ResponseCacheInterface` per array it used to
 * declare, each injected through the constructor.
 */
interface ResponseCacheInterface
{
    /**
     * Drop every entry.
     */
    public function clear(): void;

    /**
     * Drop one entry. A no-op if the key is not present.
     */
    public function delete(string $key): void;

    /**
     * @return mixed the cached value, or null if the key is not present or its cached value is
     *               itself null — mirrors isset()'s treatment of a null value as absent, which is
     *               what the private arrays this replaces relied on
     */
    public function get(string $key): mixed;

    public function has(string $key): bool;

    public function set(string $key, mixed $value): void;
}
