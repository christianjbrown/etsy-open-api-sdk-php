<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\UserAddressesTransformer;
use ChristianBrown\Etsy\Transformer\UserAddressTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class UserAddressTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_USER_ADDRESS_TRANSFORMER, UserAddressTransformer::class);
        $container->register(EtsyInterface::SERVICE_USER_ADDRESSES_TRANSFORMER, UserAddressesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_USER_ADDRESS_TRANSFORMER),
                ]
            );
    }
}
