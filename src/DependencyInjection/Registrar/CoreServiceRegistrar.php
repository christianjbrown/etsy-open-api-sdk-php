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
use ChristianBrown\Etsy\Http\FormValueEncoder;
use ChristianBrown\Etsy\Http\MultipartFormDataBuilder;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\KeyValueStore\TtlAwareKeyValueStoreInterface;
use ChristianBrown\OAuth2Client\RefreshTokenManager;
use ChristianBrown\OAuth2Client\Transformer\AccessTokenTransformer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * The services every other registrar, and every Api client, ultimately builds on: the raw
 * request senders, the shared encoders, and the OAuth `Credentials`.
 */
final class CoreServiceRegistrar implements ServiceRegistrarInterface
{
    private TtlAwareKeyValueStoreInterface $accessTokenStore;
    private string $key;
    private KeyValueStoreInterface $refreshTokenStore;
    private string $sharedSecret;

    public function __construct(string $key, string $sharedSecret, TtlAwareKeyValueStoreInterface $accessTokenStore, KeyValueStoreInterface $refreshTokenStore)
    {
        $this->key = $key;
        $this->sharedSecret = $sharedSecret;
        $this->accessTokenStore = $accessTokenStore;
        $this->refreshTokenStore = $refreshTokenStore;
    }

    public function register(ContainerBuilder $container): void
    {
        $container->register(EtsyInterface::SERVICE_API_CLIENT, ApiClient::class);
        $container->register(EtsyInterface::SERVICE_JSON_API_REQUEST_SENDER, JsonApiRequestSenderInterface::class)
            ->setFactory([new Reference(EtsyInterface::SERVICE_API_CLIENT), 'getJsonApiRequestSender']);
        $container->register(EtsyInterface::SERVICE_API_REQUEST_SENDER, ApiRequestSenderInterface::class)
            ->setFactory([new Reference(EtsyInterface::SERVICE_API_CLIENT), 'getApiRequestSender']);

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
                    EtsyInterface::OAUTH_TOKEN_URL,
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
