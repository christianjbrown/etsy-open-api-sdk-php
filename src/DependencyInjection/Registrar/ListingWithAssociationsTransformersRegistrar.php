<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ListingBuyerPriceTransformer;
use ChristianBrown\Etsy\Transformer\ListingsWithAssociationsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ListingWithAssociationsTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_LISTING_BUYER_PRICE_TRANSFORMER, ListingBuyerPriceTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_TRANSFORMER, ListingWithAssociationsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_BUYER_PRICE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_IMAGES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_PERSONALIZATION_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_TRANSLATION_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_VIDEOS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_PRODUCTION_PARTNERS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_USER_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_LISTINGS_WITH_ASSOCIATIONS_TRANSFORMER, ListingsWithAssociationsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_TRANSFORMER),
                ]
            );
    }
}
