<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ReviewsTransformer;
use ChristianBrown\Etsy\Transformer\ReviewTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ReviewTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_REVIEW_TRANSFORMER, ReviewTransformer::class);
        $container->register(EtsyInterface::SERVICE_REVIEWS_TRANSFORMER, ReviewsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_REVIEW_TRANSFORMER),
                ]
            );
    }
}
