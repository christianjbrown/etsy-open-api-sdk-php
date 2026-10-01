<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\ApiClient\ApiClientInterface;
use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformer;
use ChristianBrown\Etsy\Auth\Credentials;
use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\Etsy\Host\EtsyHostInterface;
use ChristianBrown\Etsy\Http\FormValueEncoder;
use ChristianBrown\Etsy\Http\HostRewritingApiRequestSender;
use ChristianBrown\Etsy\Http\HostRewritingJsonApiRequestSender;
use ChristianBrown\Etsy\Http\MultipartFormDataBuilder;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\KeyValueStore\TtlAwareKeyValueStoreInterface;
use ChristianBrown\OAuth2Client\Authentication\ClientAuthenticationInterface;
use ChristianBrown\OAuth2Client\Lock\LockInterface;
use ChristianBrown\OAuth2Client\RefreshTokenManagerFactoryInterface;
use ChristianBrown\OAuth2Client\RefreshTokenManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * The services every other registrar, and every Api client, ultimately builds on: the raw
 * request senders (wrapped so a configured `EtsyHostInterface` rewrites their host), the shared
 * encoders, and the OAuth `Credentials`.
 */
final class CoreServiceRegistrar implements ServiceRegistrarInterface
{
    private const string SERVICE_API_REQUEST_SENDER_INNER = 'etsy.api_request_sender.inner';
    private const string SERVICE_JSON_API_REQUEST_SENDER_INNER = 'etsy.json_api_request_sender.inner';
    private TtlAwareKeyValueStoreInterface $accessTokenStore;
    private ApiClientInterface $apiClient;
    private ClientAuthenticationInterface $clientAuthentication;
    private EtsyHostInterface $host;
    private string $key;
    private LockInterface $lock;
    private RefreshTokenManagerFactoryInterface $refreshTokenManagerFactory;
    private KeyValueStoreInterface $refreshTokenStore;
    private string $sharedSecret;

    public function __construct(string $key, string $sharedSecret, ApiClientInterface $apiClient, RefreshTokenManagerFactoryInterface $refreshTokenManagerFactory, ClientAuthenticationInterface $clientAuthentication, TtlAwareKeyValueStoreInterface $accessTokenStore, KeyValueStoreInterface $refreshTokenStore, LockInterface $lock, EtsyHostInterface $host)
    {
        $this->key = $key;
        $this->sharedSecret = $sharedSecret;
        $this->apiClient = $apiClient;
        $this->refreshTokenManagerFactory = $refreshTokenManagerFactory;
        $this->clientAuthentication = $clientAuthentication;
        $this->accessTokenStore = $accessTokenStore;
        $this->refreshTokenStore = $refreshTokenStore;
        $this->lock = $lock;
        $this->host = $host;
    }

    public function register(ContainerBuilder $container): void
    {
        $container->set(EtsyInterface::SERVICE_API_CLIENT, $this->apiClient);
        $container->register(self::SERVICE_JSON_API_REQUEST_SENDER_INNER, JsonApiRequestSenderInterface::class)
            ->setFactory([new Reference(EtsyInterface::SERVICE_API_CLIENT), 'getJsonApiRequestSender']);
        $container->register(self::SERVICE_API_REQUEST_SENDER_INNER, ApiRequestSenderInterface::class)
            ->setFactory([new Reference(EtsyInterface::SERVICE_API_CLIENT), 'getApiRequestSender']);

        $container->register(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER, HostRewritingJsonApiRequestSender::class)
            ->setArguments(
                [
                    $container->getDefinition(self::SERVICE_JSON_API_REQUEST_SENDER_INNER),
                    $this->host,
                ]
            );
        $container->register(EtsyInterface::SERVICE_API_REQUEST_SENDER, HostRewritingApiRequestSender::class)
            ->setArguments(
                [
                    $container->getDefinition(self::SERVICE_API_REQUEST_SENDER_INNER),
                    $this->host,
                ]
            );

        $container->register(EtsyInterface::SERVICE_JSON_TO_ARRAY_TRANSFORMER, JsonToArrayTransformer::class);
        $container->register(EtsyInterface::SERVICE_FORM_VALUE_ENCODER, FormValueEncoder::class);
        $container->register(EtsyInterface::SERVICE_MULTIPART_FORM_DATA_BUILDER, MultipartFormDataBuilder::class);

        $container->register(EtsyInterface::SERVICE_REFRESH_TOKEN_MANAGER, RefreshTokenManagerInterface::class)
            ->setFactory([$this->refreshTokenManagerFactory, 'create'])
            ->setArguments(
                [
                    new Reference(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->accessTokenStore,
                    $this->refreshTokenStore,
                    $this->host->getOAuthTokenUrl(),
                    $this->clientAuthentication,
                    $this->lock,
                ]
            );

        $container->register(EtsyInterface::SERVICE_CREDENTIALS, Credentials::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_REFRESH_TOKEN_MANAGER),
                    $this->key,
                    $this->sharedSecret,
                ]
            );
    }
}
