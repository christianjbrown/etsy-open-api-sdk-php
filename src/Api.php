<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy;

use ChristianBrown\Etsy\Endpoint\ReceiptsApi;
use ChristianBrown\Etsy\Endpoint\ReceiptsApiInterface;
use ChristianBrown\Etsy\Endpoint\ResultSetBasedApi;
use ChristianBrown\Etsy\Request\ApiConnector;
use ChristianBrown\Etsy\Request\ApiConnectorInterface;
use ChristianBrown\Etsy\Request\AuthenticationManager;
use ChristianBrown\Etsy\Request\AuthenticationManagerInterface;
use ChristianBrown\Etsy\Transformer\BadResponseTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\ResultSetTransformer;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\JsonApiClient\RequestSender;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\KeyValueStore\MemoryKeyValueStore;
use ChristianBrown\Oauth2Client\RefreshTokenManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class Api implements ApiInterface
{
    private ContainerInterface $container;
    private string $key;
    private KeyValueStoreInterface $refreshTokenStore;
    private int $shopId;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(int $shopId, string $key, KeyValueStoreInterface $refreshTokenStore)
    {
        $this->refreshTokenStore = $refreshTokenStore;
        $this->shopId = $shopId;
        $this->key = $key;
        $this->container = new ContainerBuilder();
        $this->init();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getReceiptsApi(): ReceiptsApiInterface
    {
        return $this->container->get('etsy.endpoint.receipts_api');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function init(): void
    {
        $this->container->set('etsy.request.refresh_token_store', $this->refreshTokenStore);

        $this->container->register('etsy.request.access_token_store', MemoryKeyValueStore::class);

        $this->container->register('etsy.request.refresh_token_manager', RefreshTokenManager::class)
            ->setArguments(
                [
                    AuthenticationManagerInterface::URL_OAUTH_TOKEN_API,
                    ApiConnectorInterface::FRIENDLY_NAME,
                    $this->container->get('etsy.request.refresh_token_store'),
                    $this->container->getDefinition('etsy.request.access_token_store'),
                ]
            );

        $this->container->register('etsy.request.authentication_manager', AuthenticationManager::class)
            ->setArguments(
                [
                    $this->container->getDefinition('etsy.request.refresh_token_manager'),
                    $this->key,
                ]
            );

        $this->container->register('etsy.request.bad_response_transformer', BadResponseTransformer::class);

        $this->container->register('etsy.request.request_sender', RequestSender::class)
            ->setArguments(
                [
                    $this->container->getDefinition('etsy.request.bad_response_transformer'),
                ]
            );

        $this->container->register('etsy.request.api_connector', ApiConnector::class)
            ->setArguments(
                [
                    $this->container->getDefinition('etsy.request.authentication_manager'),
                    $this->container->getDefinition('etsy.request.request_sender'),
                ]
            );

        $this->container->register('etsy.transformer.result_set_transformer', ResultSetTransformer::class);

        $this->container->register('etsy.endpoint.result_set_based_api', ResultSetBasedApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition('etsy.request.api_connector'),
                    $this->container->getDefinition('etsy.transformer.result_set_transformer'),
                    $this->shopId,
                ]
            );

        $this->container->register('etsy.transformer.transaction_transformer', TransactionTransformer::class);

        $this->container->register('etsy.transformer.transactions_transformer', TransactionsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition('etsy.transformer.transaction_transformer'),
                ]
            );

        $this->container->register('etsy.transformer.receipt_transformer', ReceiptTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition('etsy.transformer.transactions_transformer'),
                ]
            );

        $this->container->register('etsy.transformer.receipts_transformer', ReceiptsTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition('etsy.transformer.receipt_transformer'),
                ]
            );

        $this->container->register('etsy.endpoint.receipts_api', ReceiptsApi::class)
            ->setArguments(
                [
                    $this->container->getDefinition('etsy.endpoint.result_set_based_api'),
                    $this->container->getDefinition('etsy.transformer.receipts_transformer'),
                ]
            );
    }
}
