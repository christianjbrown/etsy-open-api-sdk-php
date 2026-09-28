<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferencesTransformer;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferenceTransformer;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnersTransformer;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnerTransformer;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionsTransformer;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionTransformer;
use ChristianBrown\Etsy\Transformer\ShopReturnPoliciesTransformer;
use ChristianBrown\Etsy\Transformer\ShopReturnPolicyTransformer;
use ChristianBrown\Etsy\Transformer\ShopSectionsTransformer;
use ChristianBrown\Etsy\Transformer\ShopSectionTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ShopConfigurationTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_SHOP_SECTION_TRANSFORMER, ShopSectionTransformer::class);
        $container->register(EtsyInterface::SERVICE_SHOP_SECTIONS_TRANSFORMER, ShopSectionsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SECTION_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_RETURN_POLICY_TRANSFORMER, ShopReturnPolicyTransformer::class);
        $container->register(EtsyInterface::SERVICE_SHOP_RETURN_POLICIES_TRANSFORMER, ShopReturnPoliciesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_RETURN_POLICY_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_PRODUCTION_PARTNER_TRANSFORMER, ShopProductionPartnerTransformer::class);
        $container->register(EtsyInterface::SERVICE_SHOP_PRODUCTION_PARTNERS_TRANSFORMER, ShopProductionPartnersTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_PRODUCTION_PARTNER_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_HOLIDAY_PREFERENCE_TRANSFORMER, ShopHolidayPreferenceTransformer::class);
        $container->register(EtsyInterface::SERVICE_SHOP_HOLIDAY_PREFERENCES_TRANSFORMER, ShopHolidayPreferencesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_HOLIDAY_PREFERENCE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_SHOP_READINESS_STATE_DEFINITION_TRANSFORMER, ShopReadinessStateDefinitionTransformer::class);
        $container->register(EtsyInterface::SERVICE_SHOP_READINESS_STATE_DEFINITIONS_TRANSFORMER, ShopReadinessStateDefinitionsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_READINESS_STATE_DEFINITION_TRANSFORMER),
                ]
            );
    }
}
