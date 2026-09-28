<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ListingsTransformer;
use ChristianBrown\Etsy\Transformer\ListingTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ListingTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_LISTING_TRANSFORMER, ListingTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_LISTINGS_TRANSFORMER, ListingsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_TRANSFORMER),
                ]
            );
    }
}
