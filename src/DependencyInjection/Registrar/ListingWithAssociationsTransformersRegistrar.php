<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ListingBuyerPriceTransformer;
use ChristianBrown\Etsy\Transformer\ListingsWithAssociationsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsCatalogFieldsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsDimensionsFieldsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsEcgtFieldsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsFlagsFieldsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsIdentifiersFieldsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsListsFieldsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsMediaFieldsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsPriceFieldsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsSellerFieldsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsTextFieldsTransformer;
use ChristianBrown\Etsy\Transformer\ListingWithAssociationsTimestampsFieldsTransformer;
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

        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_TIMESTAMPS_FIELDS_TRANSFORMER, ListingWithAssociationsTimestampsFieldsTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_FLAGS_FIELDS_TRANSFORMER, ListingWithAssociationsFlagsFieldsTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_TEXT_FIELDS_TRANSFORMER, ListingWithAssociationsTextFieldsTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_DIMENSIONS_FIELDS_TRANSFORMER, ListingWithAssociationsDimensionsFieldsTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_ECGT_FIELDS_TRANSFORMER, ListingWithAssociationsEcgtFieldsTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_LISTS_FIELDS_TRANSFORMER, ListingWithAssociationsListsFieldsTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_IDENTIFIERS_FIELDS_TRANSFORMER, ListingWithAssociationsIdentifiersFieldsTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_PRICE_FIELDS_TRANSFORMER, ListingWithAssociationsPriceFieldsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_BUYER_PRICE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_MEDIA_FIELDS_TRANSFORMER, ListingWithAssociationsMediaFieldsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_IMAGES_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_VIDEOS_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_CATALOG_FIELDS_TRANSFORMER, ListingWithAssociationsCatalogFieldsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_INVENTORY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_PERSONALIZATION_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_TRANSLATION_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_SELLER_FIELDS_TRANSFORMER, ListingWithAssociationsSellerFieldsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_PRODUCTION_PARTNERS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_SHIPPING_PROFILE_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHOP_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_USER_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_TRANSFORMER, ListingWithAssociationsTransformer::class)
            ->setArguments(
                [
                    [
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_TIMESTAMPS_FIELDS_TRANSFORMER),
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_FLAGS_FIELDS_TRANSFORMER),
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_TEXT_FIELDS_TRANSFORMER),
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_DIMENSIONS_FIELDS_TRANSFORMER),
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_ECGT_FIELDS_TRANSFORMER),
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_LISTS_FIELDS_TRANSFORMER),
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_IDENTIFIERS_FIELDS_TRANSFORMER),
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_PRICE_FIELDS_TRANSFORMER),
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_MEDIA_FIELDS_TRANSFORMER),
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_CATALOG_FIELDS_TRANSFORMER),
                        $container->getDefinition(EtsyInterface::SERVICE_LISTING_WITH_ASSOCIATIONS_SELLER_FIELDS_TRANSFORMER),
                    ],
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
