<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ShopsTransformer;
use ChristianBrown\Etsy\Transformer\ShopTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ShopTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_SHOP_TRANSFORMER, ShopTransformer::class);
        $container->register(EtsyInterface::SERVICE_SHOPS_TRANSFORMER, ShopsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_TRANSFORMER),
                ]
            );
    }
}
