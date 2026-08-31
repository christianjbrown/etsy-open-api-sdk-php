<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\CreateShopReadinessStateDefinitionRequestInterface;
use ChristianBrown\Etsy\Model\ShopReadinessStateDefinitionInterface;
use ChristianBrown\Etsy\Model\UpdateShopReadinessStateDefinitionRequestInterface;
use ChristianBrown\Etsy\Serializer\CreateShopReadinessStateDefinitionRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopReadinessStateDefinitionRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionTransformerInterface;

use function is_array;
use function sprintf;

final class ShopReadinessStateDefinitionApi implements ShopReadinessStateDefinitionApiInterface
{
    private ApiRequestSenderInterface $apiRequestSender;

    /**
     * @var null|array<int, ShopReadinessStateDefinitionInterface>
     */
    private ?array $cache = null;
    private CreateShopReadinessStateDefinitionRequestSerializerInterface $createShopReadinessStateDefinitionRequestSerializer;
    private CredentialsInterface $credentials;

    /**
     * @var array<int, ShopReadinessStateDefinitionInterface>
     */
    private array $definitionCache = [];
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private ShopReadinessStateDefinitionsTransformerInterface $shopReadinessStateDefinitionsTransformer;
    private ShopReadinessStateDefinitionTransformerInterface $shopReadinessStateDefinitionTransformer;
    private UpdateShopReadinessStateDefinitionRequestSerializerInterface $updateShopReadinessStateDefinitionRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ShopReadinessStateDefinitionTransformerInterface $shopReadinessStateDefinitionTransformer, ShopReadinessStateDefinitionsTransformerInterface $shopReadinessStateDefinitionsTransformer, CreateShopReadinessStateDefinitionRequestSerializerInterface $createShopReadinessStateDefinitionRequestSerializer, UpdateShopReadinessStateDefinitionRequestSerializerInterface $updateShopReadinessStateDefinitionRequestSerializer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->shopReadinessStateDefinitionTransformer = $shopReadinessStateDefinitionTransformer;
        $this->shopReadinessStateDefinitionsTransformer = $shopReadinessStateDefinitionsTransformer;
        $this->createShopReadinessStateDefinitionRequestSerializer = $createShopReadinessStateDefinitionRequestSerializer;
        $this->updateShopReadinessStateDefinitionRequestSerializer = $updateShopReadinessStateDefinitionRequestSerializer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function create(CreateShopReadinessStateDefinitionRequestInterface $createShopReadinessStateDefinitionRequest): ShopReadinessStateDefinitionInterface
    {
        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->postForm($url, [], $this->credentials->toHeaders(), $this->createShopReadinessStateDefinitionRequestSerializer->serialize($createShopReadinessStateDefinitionRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopReadinessStateDefinition = $this->shopReadinessStateDefinitionTransformer->transform($data);
        $this->cache = null;

        return $shopReadinessStateDefinition;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $readinessStateDefinitionId): void
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $readinessStateDefinitionId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->cache = null;
        unset($this->definitionCache[$readinessStateDefinitionId]);
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShopReadinessStateDefinitionInterface>
     */
    public function getMultiple(bool $skipCache = false): array
    {
        if (!$skipCache) {
            if (null !== $this->cache) {
                return $this->cache;
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $shopReadinessStateDefinitions = $this->shopReadinessStateDefinitionsTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache = $shopReadinessStateDefinitions;

        return $shopReadinessStateDefinitions;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $readinessStateDefinitionId, bool $skipCache = false): ShopReadinessStateDefinitionInterface
    {
        if (!$skipCache) {
            if (isset($this->definitionCache[$readinessStateDefinitionId])) {
                return $this->definitionCache[$readinessStateDefinitionId];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $readinessStateDefinitionId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopReadinessStateDefinition = $this->shopReadinessStateDefinitionTransformer->transform($data);
        $this->definitionCache[$readinessStateDefinitionId] = $shopReadinessStateDefinition;

        return $shopReadinessStateDefinition;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function update(int $readinessStateDefinitionId, UpdateShopReadinessStateDefinitionRequestInterface $updateShopReadinessStateDefinitionRequest): ShopReadinessStateDefinitionInterface
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $readinessStateDefinitionId);
        $data = $this->requestSender->putForm($url, [], $this->credentials->toHeaders(), $this->updateShopReadinessStateDefinitionRequestSerializer->serialize($updateShopReadinessStateDefinitionRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopReadinessStateDefinition = $this->shopReadinessStateDefinitionTransformer->transform($data);
        $this->cache = null;
        $this->definitionCache[$readinessStateDefinitionId] = $shopReadinessStateDefinition;

        return $shopReadinessStateDefinition;
    }
}
