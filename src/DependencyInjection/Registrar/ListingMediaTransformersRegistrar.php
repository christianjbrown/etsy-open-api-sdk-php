<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ListingFilesTransformer;
use ChristianBrown\Etsy\Transformer\ListingFileTransformer;
use ChristianBrown\Etsy\Transformer\ListingImagesTransformer;
use ChristianBrown\Etsy\Transformer\ListingImageTransformer;
use ChristianBrown\Etsy\Transformer\ListingVariationImagesTransformer;
use ChristianBrown\Etsy\Transformer\ListingVariationImageTransformer;
use ChristianBrown\Etsy\Transformer\ListingVideosTransformer;
use ChristianBrown\Etsy\Transformer\ListingVideoTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ListingMediaTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_LISTING_FILE_TRANSFORMER, ListingFileTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_FILES_TRANSFORMER, ListingFilesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_FILE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_IMAGE_TRANSFORMER, ListingImageTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_IMAGES_TRANSFORMER, ListingImagesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_IMAGE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_VIDEO_TRANSFORMER, ListingVideoTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_VIDEOS_TRANSFORMER, ListingVideosTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_VIDEO_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_VARIATION_IMAGE_TRANSFORMER, ListingVariationImageTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_VARIATION_IMAGES_TRANSFORMER, ListingVariationImagesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_VARIATION_IMAGE_TRANSFORMER),
                ]
            );
    }
}
