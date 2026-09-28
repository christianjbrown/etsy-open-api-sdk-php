<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarrierMailClassesTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarrierMailClassTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarriersTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarrierTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationsTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfilesTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradesTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradeTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ShippingProfileTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_SHIPPING_CARRIER_MAIL_CLASS_TRANSFORMER, ShippingCarrierMailClassTransformer::class);
        $container->register(EtsyInterface::SERVICE_SHIPPING_CARRIER_MAIL_CLASSES_TRANSFORMER, ShippingCarrierMailClassesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHIPPING_CARRIER_MAIL_CLASS_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHIPPING_CARRIER_TRANSFORMER, ShippingCarrierTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHIPPING_CARRIER_MAIL_CLASSES_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_SHIPPING_CARRIERS_TRANSFORMER, ShippingCarriersTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHIPPING_CARRIER_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATION_TRANSFORMER, ShopShippingProfileDestinationTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATIONS_TRANSFORMER, ShopShippingProfileDestinationsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATION_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADE_TRANSFORMER, ShopShippingProfileUpgradeTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADES_TRANSFORMER, ShopShippingProfileUpgradesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_TRANSFORMER, ShopShippingProfileTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_DESTINATIONS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_UPGRADES_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILES_TRANSFORMER, ShopShippingProfilesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_TRANSFORMER),
                ]
            );
    }
}
