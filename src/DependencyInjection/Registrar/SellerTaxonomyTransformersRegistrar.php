<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodesTransformer;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodeTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertiesTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertyTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyScalesTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyScaleTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyValueTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SellerTaxonomyTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_SELLER_TAXONOMY_NODE_TRANSFORMER, SellerTaxonomyNodeTransformer::class);
        $container->register(EtsyInterface::SERVICE_SELLER_TAXONOMY_NODES_TRANSFORMER, SellerTaxonomyNodesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SELLER_TAXONOMY_NODE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_TAXONOMY_PROPERTY_SCALE_TRANSFORMER, TaxonomyPropertyScaleTransformer::class);
        $container->register(EtsyInterface::SERVICE_TAXONOMY_PROPERTY_SCALES_TRANSFORMER, TaxonomyPropertyScalesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_TAXONOMY_PROPERTY_SCALE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_TAXONOMY_PROPERTY_VALUE_TRANSFORMER, TaxonomyPropertyValueTransformer::class);
        $container->register(EtsyInterface::SERVICE_TAXONOMY_PROPERTY_VALUES_TRANSFORMER, TaxonomyPropertyValuesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_TAXONOMY_PROPERTY_VALUE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_TAXONOMY_NODE_PROPERTY_TRANSFORMER, TaxonomyNodePropertyTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_TAXONOMY_PROPERTY_SCALES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_TAXONOMY_PROPERTY_VALUES_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_TAXONOMY_NODE_PROPERTIES_TRANSFORMER, TaxonomyNodePropertiesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_TAXONOMY_NODE_PROPERTY_TRANSFORMER),
                ]
            );
    }
}
