<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertiesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertyTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodeTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyScalesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyScaleTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyValueTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class BuyerTaxonomyTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_BUYER_TAXONOMY_NODE_TRANSFORMER, BuyerTaxonomyNodeTransformer::class);
        $container->register(EtsyInterface::SERVICE_BUYER_TAXONOMY_NODES_TRANSFORMER, BuyerTaxonomyNodesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_BUYER_TAXONOMY_NODE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_BUYER_TAXONOMY_PROPERTY_SCALE_TRANSFORMER, BuyerTaxonomyPropertyScaleTransformer::class);
        $container->register(EtsyInterface::SERVICE_BUYER_TAXONOMY_PROPERTY_SCALES_TRANSFORMER, BuyerTaxonomyPropertyScalesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_BUYER_TAXONOMY_PROPERTY_SCALE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_BUYER_TAXONOMY_PROPERTY_VALUE_TRANSFORMER, BuyerTaxonomyPropertyValueTransformer::class);
        $container->register(EtsyInterface::SERVICE_BUYER_TAXONOMY_PROPERTY_VALUES_TRANSFORMER, BuyerTaxonomyPropertyValuesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_BUYER_TAXONOMY_PROPERTY_VALUE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_BUYER_TAXONOMY_NODE_PROPERTY_TRANSFORMER, BuyerTaxonomyNodePropertyTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_BUYER_TAXONOMY_PROPERTY_SCALES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_BUYER_TAXONOMY_PROPERTY_VALUES_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_BUYER_TAXONOMY_NODE_PROPERTIES_TRANSFORMER, BuyerTaxonomyNodePropertiesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_BUYER_TAXONOMY_NODE_PROPERTY_TRANSFORMER),
                ]
            );
    }
}
