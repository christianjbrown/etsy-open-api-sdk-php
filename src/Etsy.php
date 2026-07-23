<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy;

use ChristianBrown\ApiClient\ApiClient;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\PingApi;
use ChristianBrown\Etsy\Api\PingApiInterface;
use ChristianBrown\Etsy\Api\ShopApi;
use ChristianBrown\Etsy\Api\ShopApiInterface;
use ChristianBrown\Etsy\Api\ShopReceiptApi;
use ChristianBrown\Etsy\Api\ShopReceiptApiInterface;
use ChristianBrown\Etsy\Api\UserAddressApi;
use ChristianBrown\Etsy\Api\UserAddressApiInterface;
use ChristianBrown\Etsy\Api\UserApi;
use ChristianBrown\Etsy\Api\UserApiInterface;
use ChristianBrown\Etsy\Auth\Credentials;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\ListingPropertyValueTransformer;
use ChristianBrown\Etsy\Transformer\MoneyTransformer;
use ChristianBrown\Etsy\Transformer\PingTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\RefundsTransformer;
use ChristianBrown\Etsy\Transformer\RefundTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentsTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentTransformer;
use ChristianBrown\Etsy\Transformer\ShopsTransformer;
use ChristianBrown\Etsy\Transformer\ShopTransformer;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationTransformer;
use ChristianBrown\Etsy\Transformer\UserAddressesTransformer;
use ChristianBrown\Etsy\Transformer\UserAddressTransformer;
use ChristianBrown\Etsy\Transformer\UserTransformer;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\OAuth2Client\RefreshTokenManager;
use ChristianBrown\OAuth2Client\Transformer\AccessTokenTransformer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class Etsy implements EtsyInterface
{
    private KeyValueStoreInterface $accessTokenStore;
    private ContainerBuilder $container;
    private string $key;
    private KeyValueStoreInterface $refreshTokenStore;
    private int $shopId;

    public function __construct(int $shopId, string $key, KeyValueStoreInterface $accessTokenStore, KeyValueStoreInterface $refreshTokenStore)
    {
        $this->shopId = $shopId;
        $this->key = $key;
        $this->accessTokenStore = $accessTokenStore;
        $this->refreshTokenStore = $refreshTokenStore;
        $this->container = new ContainerBuilder();
        $this->init();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getPingApi(): PingApiInterface
    {
        /**
         * @var PingApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_PING_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopApi(): ShopApiInterface
    {
        /**
         * @var ShopApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getShopReceiptApi(): ShopReceiptApiInterface
    {
        /**
         * @var ShopReceiptApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_SHOP_RECEIPT_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUserAddressApi(): UserAddressApiInterface
    {
        /**
         * @var UserAddressApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_USER_ADDRESS_API);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUserApi(): UserApiInterface
    {
        /**
         * @var UserApiInterface $service
         */
        $service = $this->container->get(self::SERVICE_USER_API);

        return $service;
    }

    private function init(): void
    {
        // Registration order matters: a service must be registered before another
        // service wires a reference to its definition, so core comes first and the
        // API clients (which reference every transformer chain) come last.
        $this->registerCore();
        $this->registerReceiptTransformers();
        $this->registerShopTransformers();
        $this->registerUserTransformers();
        $this->registerUserAddressTransformers();
        $this->registerPingTransformers();
        $this->registerApiClients();
    }

    private function registerApiClients(): void
    {
        $this->container->register(self::SERVICE_SHOP_RECEIPT_API, ShopReceiptApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_RECEIPT_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_RECEIPTS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_SHOP_API, ShopApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_SHOP_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHOPS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                    $this->shopId,
                ]
            );

        $this->container->register(self::SERVICE_USER_API, UserApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_USER_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_USER_ADDRESS_API, UserAddressApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_USER_ADDRESS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_USER_ADDRESSES_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );

        $this->container->register(self::SERVICE_PING_API, PingApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_PING_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_CREDENTIALS),
                ]
            );
    }

    private function registerCore(): void
    {
        $this->container->register(self::SERVICE_API_CLIENT, ApiClient::class);
        $this->container->register(self::SERVICE_JSON_API_REQUEST_SENDER, JsonApiRequestSenderInterface::class)
            ->setFactory([new Reference(self::SERVICE_API_CLIENT), 'getJsonApiRequestSender']);

        $this->container->register(self::SERVICE_ACCESS_TOKEN_TRANSFORMER, AccessTokenTransformer::class);

        $this->container->register(self::SERVICE_REFRESH_TOKEN_MANAGER, RefreshTokenManager::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->accessTokenStore,
                    $this->refreshTokenStore,
                    $this->container->getDefinition(self::SERVICE_ACCESS_TOKEN_TRANSFORMER),
                    self::OAUTH_TOKEN_URL,
                ]
            );

        $this->container->register(self::SERVICE_CREDENTIALS, Credentials::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_REFRESH_TOKEN_MANAGER),
                    $this->key,
                ]
            );
    }

    private function registerPingTransformers(): void
    {
        $this->container->register(self::SERVICE_PING_TRANSFORMER, PingTransformer::class);
    }

    private function registerReceiptTransformers(): void
    {
        $this->container->register(self::SERVICE_MONEY_TRANSFORMER, MoneyTransformer::class);

        $this->container->register(self::SERVICE_SHIPMENT_TRANSFORMER, ShipmentTransformer::class);
        $this->container->register(self::SERVICE_SHIPMENTS_TRANSFORMER, ShipmentsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHIPMENT_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_REFUND_TRANSFORMER, RefundTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_REFUNDS_TRANSFORMER, RefundsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_REFUND_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_TRANSACTION_VARIATION_TRANSFORMER, TransactionVariationTransformer::class);
        $this->container->register(self::SERVICE_TRANSACTION_VARIATIONS_TRANSFORMER, TransactionVariationsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_TRANSACTION_VARIATION_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_LISTING_PROPERTY_VALUE_TRANSFORMER, ListingPropertyValueTransformer::class);
        $this->container->register(self::SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER, ListingPropertyValuesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_LISTING_PROPERTY_VALUE_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_TRANSACTION_TRANSFORMER, TransactionTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_TRANSACTION_VARIATIONS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_TRANSACTIONS_TRANSFORMER, TransactionsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_TRANSACTION_TRANSFORMER),
                ]
            );

        $this->container->register(self::SERVICE_RECEIPT_TRANSFORMER, ReceiptTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_MONEY_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_TRANSACTIONS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_REFUNDS_TRANSFORMER),
                    $this->container->getDefinition(self::SERVICE_SHIPMENTS_TRANSFORMER),
                ]
            );
        $this->container->register(self::SERVICE_RECEIPTS_TRANSFORMER, ReceiptsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_RECEIPT_TRANSFORMER),
                ]
            );
    }

    private function registerShopTransformers(): void
    {
        $this->container->register(self::SERVICE_SHOP_TRANSFORMER, ShopTransformer::class);
        $this->container->register(self::SERVICE_SHOPS_TRANSFORMER, ShopsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_SHOP_TRANSFORMER),
                ]
            );
    }

    private function registerUserAddressTransformers(): void
    {
        $this->container->register(self::SERVICE_USER_ADDRESS_TRANSFORMER, UserAddressTransformer::class);
        $this->container->register(self::SERVICE_USER_ADDRESSES_TRANSFORMER, UserAddressesTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_USER_ADDRESS_TRANSFORMER),
                ]
            );
    }

    private function registerUserTransformers(): void
    {
        $this->container->register(self::SERVICE_USER_TRANSFORMER, UserTransformer::class);
    }
}
