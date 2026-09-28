<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PaymentAdjustmentTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_PAYMENT_ADJUSTMENT_ITEM_TRANSFORMER, PaymentAdjustmentItemTransformer::class);
        $container->register(EtsyInterface::SERVICE_PAYMENT_ADJUSTMENT_ITEMS_TRANSFORMER, PaymentAdjustmentItemsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PAYMENT_ADJUSTMENT_ITEM_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_PAYMENT_ADJUSTMENT_TRANSFORMER, PaymentAdjustmentTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PAYMENT_ADJUSTMENT_ITEMS_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_PAYMENT_ADJUSTMENTS_TRANSFORMER, PaymentAdjustmentsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PAYMENT_ADJUSTMENT_TRANSFORMER),
                ]
            );
    }
}
