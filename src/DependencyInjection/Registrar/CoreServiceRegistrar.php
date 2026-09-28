<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection\Registrar;

use ChristianBrown\ApiClient\ApiClient;
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
use ChristianBrown\OAuth2Client\RefreshTokenManager;
use ChristianBrown\OAuth2Client\Transformer\AccessTokenTransformer;
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
    private EtsyHostInterface $host;
    private string $key;
    private KeyValueStoreInterface $refreshTokenStore;
    private string $sharedSecret;

    public function __construct(string $key, string $sharedSecret, TtlAwareKeyValueStoreInterface $accessTokenStore, KeyValueStoreInterface $refreshTokenStore, EtsyHostInterface $host)
    {
        $this->key = $key;
        $this->sharedSecret = $sharedSecret;
        $this->accessTokenStore = $accessTokenStore;
        $this->refreshTokenStore = $refreshTokenStore;
        $this->host = $host;
    }

    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_API_CLIENT, ApiClient::class);
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

        $container->register(EtsyInterface::SERVICE_ACCESS_TOKEN_TRANSFORMER, AccessTokenTransformer::class);

        $container->register(EtsyInterface::SERVICE_REFRESH_TOKEN_MANAGER, RefreshTokenManager::class)
            ->setArguments(
                [
                    $container->getDefinition(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER),
                    $this->accessTokenStore,
                    $this->refreshTokenStore,
                    $container->getDefinition(EtsyInterface::SERVICE_ACCESS_TOKEN_TRANSFORMER),
                    $this->host->getOAuthTokenUrl(),
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
