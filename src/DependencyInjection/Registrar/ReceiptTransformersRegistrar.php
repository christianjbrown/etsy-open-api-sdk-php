<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\ListingPropertyValueTransformer;
use ChristianBrown\Etsy\Transformer\MoneyTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptPageTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\RefundsTransformer;
use ChristianBrown\Etsy\Transformer\RefundTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentsTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentTransformer;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ReceiptTransformersRegistrar implements ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_MONEY_TRANSFORMER, MoneyTransformer::class);

        $container->register(EtsyInterface::SERVICE_SHIPMENT_TRANSFORMER, ShipmentTransformer::class);
        $container->register(EtsyInterface::SERVICE_SHIPMENTS_TRANSFORMER, ShipmentsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_SHIPMENT_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_REFUND_TRANSFORMER, RefundTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_REFUNDS_TRANSFORMER, RefundsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_REFUND_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_TRANSACTION_VARIATION_TRANSFORMER, TransactionVariationTransformer::class);
        $container->register(EtsyInterface::SERVICE_TRANSACTION_VARIATIONS_TRANSFORMER, TransactionVariationsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_TRANSACTION_VARIATION_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_LISTING_PROPERTY_VALUE_TRANSFORMER, ListingPropertyValueTransformer::class);
        $container->register(EtsyInterface::SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER, ListingPropertyValuesTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_PROPERTY_VALUE_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_TRANSACTION_TRANSFORMER, TransactionTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_TRANSACTION_VARIATIONS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_TRANSACTIONS_TRANSFORMER, TransactionsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_TRANSACTION_TRANSFORMER),
                ]
            );

        $container->register(EtsyInterface::SERVICE_RECEIPT_TRANSFORMER, ReceiptTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_MONEY_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_TRANSACTIONS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_REFUNDS_TRANSFORMER),
                    $container->getDefinition(EtsyInterface::SERVICE_SHIPMENTS_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_RECEIPTS_TRANSFORMER, ReceiptsTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_RECEIPT_TRANSFORMER),
                ]
            );
        $container->register(EtsyInterface::SERVICE_RECEIPT_PAGE_TRANSFORMER, ReceiptPageTransformer::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_RECEIPTS_TRANSFORMER),
                ]
            );
    }
}
