<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy;

use ChristianBrown\ApiClient\ApiClientFactory;
use ChristianBrown\ApiClient\ClientOptions;
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
use ChristianBrown\Etsy\Host\EtsyHost;
use ChristianBrown\Etsy\Host\EtsyHostInterface;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\KeyValueStore\TtlAwareKeyValueStoreInterface;
use ChristianBrown\OAuth2Client\Authentication\PublicClientAuthentication;
use ChristianBrown\OAuth2Client\Lock\NullLock;
use ChristianBrown\OAuth2Client\RefreshTokenManagerFactory;
use Symfony\Component\Clock\NativeClock;

/**
 * The composition root: the one place that builds the registrars, the container and the facade.
 * Etsy refreshes with a public client (the keystring is the client id) and takes no lock, so
 * token refreshes are not serialised across processes.
 */
final class EtsyFactory implements EtsyFactoryInterface
{
    public function create(int $shopId, string $key, string $sharedSecret, TtlAwareKeyValueStoreInterface $accessTokenStore, KeyValueStoreInterface $refreshTokenStore): EtsyInterface
    {
        return $this->createForHost($shopId, $key, $sharedSecret, $accessTokenStore, $refreshTokenStore, new EtsyHost());
    }

    public function createForHost(int $shopId, string $key, string $sharedSecret, TtlAwareKeyValueStoreInterface $accessTokenStore, KeyValueStoreInterface $refreshTokenStore, EtsyHostInterface $host): EtsyInterface
    {
        $apiClient = (new ApiClientFactory(new ClientOptions()))->create();
        $refreshTokenManagerFactory = new RefreshTokenManagerFactory(new NativeClock());

        $containerFactory = new ContainerFactory(
            [
                new CoreServiceRegistrar($key, $sharedSecret, $apiClient, $refreshTokenManagerFactory, new PublicClientAuthentication(), $accessTokenStore, $refreshTokenStore, new NullLock(), $host),
                new RequestSerializersRegistrar(),
                new ReceiptTransformersRegistrar(),
                new ListingTransformersRegistrar(),
                new ListingInventoryTransformersRegistrar(),
                new ListingMediaTransformersRegistrar(),
                new ListingTranslationTransformersRegistrar(),
                new ListingPersonalizationTransformersRegistrar(),
                new ShopTransformersRegistrar(),
                new ShopConfigurationTransformersRegistrar(),
                new ShippingProfileTransformersRegistrar(),
                new UserTransformersRegistrar(),
                new UserAddressTransformersRegistrar(),
                new PingTransformersRegistrar(),
                new PaymentAdjustmentTransformersRegistrar(),
                new PaymentTransformersRegistrar(),
                new LedgerTransformersRegistrar(),
                new ReviewTransformersRegistrar(),
                new SellerTaxonomyTransformersRegistrar(),
                new BuyerTaxonomyTransformersRegistrar(),
                new ListingWithAssociationsTransformersRegistrar(),
                new ApiClientsRegistrar($shopId),
            ]
        );

        return new Etsy($containerFactory->create());
    }
}
