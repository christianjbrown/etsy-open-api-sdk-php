<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy;

use ChristianBrown\Etsy\Host\EtsyHostInterface;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\KeyValueStore\TtlAwareKeyValueStoreInterface;

interface EtsyFactoryInterface
{
    public function create(int $shopId, string $key, string $sharedSecret, TtlAwareKeyValueStoreInterface $accessTokenStore, KeyValueStoreInterface $refreshTokenStore): EtsyInterface;

    public function createForHost(int $shopId, string $key, string $sharedSecret, TtlAwareKeyValueStoreInterface $accessTokenStore, KeyValueStoreInterface $refreshTokenStore, EtsyHostInterface $host): EtsyInterface;
}
