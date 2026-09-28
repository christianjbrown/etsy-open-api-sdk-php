<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntriesTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntryTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class LedgerTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRY_TRANSFORMER, PaymentAccountLedgerEntryTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PAYMENT_ADJUSTMENTS_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRIES_TRANSFORMER, PaymentAccountLedgerEntriesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_PAYMENT_ACCOUNT_LEDGER_ENTRY_TRANSFORMER),
                ]
            );
    }
}
