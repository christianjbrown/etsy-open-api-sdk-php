<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\PaymentsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PaymentTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_PAYMENT_TRANSFORMER, PaymentTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_PAYMENT_ADJUSTMENTS_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_PAYMENTS_TRANSFORMER, PaymentsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PAYMENT_TRANSFORMER),
                ]
            );
    }
}
