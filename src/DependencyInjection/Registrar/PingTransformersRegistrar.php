<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\PingTransformer;
use ChristianBrown\Etsy\Transformer\ScopesTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PingTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_PING_TRANSFORMER, PingTransformer::class);
        $container->register(EtsyInterface::SERVICE_SCOPES_TRANSFORMER, ScopesTransformer::class);
    }
}
