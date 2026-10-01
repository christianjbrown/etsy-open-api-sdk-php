<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests;

use ChristianBrown\Etsy\DependencyInjection\ContainerFactory;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ApiClientsRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\BuyerTaxonomyTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\CoreServiceRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\LedgerTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ListingInventoryTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ListingMediaTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ListingPersonalizationTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ListingTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ListingTranslationTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ListingWithAssociationsTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\PaymentAdjustmentTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\PaymentTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\PingTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ReceiptTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\RequestSerializersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ReviewTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\SellerTaxonomyTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ShippingProfileTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ShopConfigurationTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\ShopTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\UserAddressTransformersRegistrar;
use ChristianBrown\Etsy\DependencyInjection\Registrar\UserTransformersRegistrar;
use ChristianBrown\Etsy\Etsy;
use ChristianBrown\Etsy\EtsyFactory;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Host\EtsyHost;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\KeyValueStore\TtlAwareKeyValueStoreInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EtsyFactory::class)]
#[UsesClass(Etsy::class)]
#[UsesClass(ContainerFactory::class)]
#[UsesClass(EtsyHost::class)]
#[CoversClass(ApiClientsRegistrar::class)]
#[CoversClass(BuyerTaxonomyTransformersRegistrar::class)]
#[CoversClass(CoreServiceRegistrar::class)]
#[CoversClass(LedgerTransformersRegistrar::class)]
#[CoversClass(ListingInventoryTransformersRegistrar::class)]
#[CoversClass(ListingMediaTransformersRegistrar::class)]
#[CoversClass(ListingPersonalizationTransformersRegistrar::class)]
#[CoversClass(ListingTransformersRegistrar::class)]
#[CoversClass(ListingTranslationTransformersRegistrar::class)]
#[CoversClass(ListingWithAssociationsTransformersRegistrar::class)]
#[CoversClass(PaymentAdjustmentTransformersRegistrar::class)]
#[CoversClass(PaymentTransformersRegistrar::class)]
#[CoversClass(PingTransformersRegistrar::class)]
#[CoversClass(ReceiptTransformersRegistrar::class)]
#[CoversClass(RequestSerializersRegistrar::class)]
#[CoversClass(ReviewTransformersRegistrar::class)]
#[CoversClass(SellerTaxonomyTransformersRegistrar::class)]
#[CoversClass(ShippingProfileTransformersRegistrar::class)]
#[CoversClass(ShopConfigurationTransformersRegistrar::class)]
#[CoversClass(ShopTransformersRegistrar::class)]
#[CoversClass(UserAddressTransformersRegistrar::class)]
#[CoversClass(UserTransformersRegistrar::class)]
final class EtsyFactoryTest extends TestCase
{
    public function testCreateAcceptsACustomHost(): void
    {
        $etsy = (new EtsyFactory())->create(42, 'key', 'secret', self::createStub(TtlAwareKeyValueStoreInterface::class), self::createStub(KeyValueStoreInterface::class), new EtsyHost('https://example.test'));

        self::assertInstanceOf(EtsyInterface::class, $etsy);
    }

    public function testCreateBuildsTheFacadeWithTheDefaultHost(): void
    {
        $etsy = (new EtsyFactory())->create(42, 'key', 'secret', self::createStub(TtlAwareKeyValueStoreInterface::class), self::createStub(KeyValueStoreInterface::class));

        self::assertInstanceOf(EtsyInterface::class, $etsy);
    }
}
