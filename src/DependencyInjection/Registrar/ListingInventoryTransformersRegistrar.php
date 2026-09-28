<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingsTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductsTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ListingInventoryTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_TRANSFORMER, ListingInventoryProductOfferingTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERINGS_TRANSFORMER, ListingInventoryProductOfferingsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERING_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_TRANSFORMER, ListingInventoryProductTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_OFFERINGS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCTS_TRANSFORMER, ListingInventoryProductsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCT_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_INVENTORY_TRANSFORMER, ListingInventoryTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_PRODUCTS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_TRANSFORMER),
                ]
            );
    }
}
