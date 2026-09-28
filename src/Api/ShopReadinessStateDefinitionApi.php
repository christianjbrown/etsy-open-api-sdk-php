<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
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
    private ResponseCacheInterface $cache;
    private CreateShopReadinessStateDefinitionRequestSerializerInterface $createShopReadinessStateDefinitionRequestSerializer;
    private CredentialsInterface $credentials;
    private ResponseCacheInterface $definitionCache;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private ShopReadinessStateDefinitionsTransformerInterface $shopReadinessStateDefinitionsTransformer;
    private ShopReadinessStateDefinitionTransformerInterface $shopReadinessStateDefinitionTransformer;
    private UpdateShopReadinessStateDefinitionRequestSerializerInterface $updateShopReadinessStateDefinitionRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ShopReadinessStateDefinitionTransformerInterface $shopReadinessStateDefinitionTransformer, ShopReadinessStateDefinitionsTransformerInterface $shopReadinessStateDefinitionsTransformer, CreateShopReadinessStateDefinitionRequestSerializerInterface $createShopReadinessStateDefinitionRequestSerializer, UpdateShopReadinessStateDefinitionRequestSerializerInterface $updateShopReadinessStateDefinitionRequestSerializer, ResponseCacheInterface $definitionCache, ResponseCacheInterface $cache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->shopReadinessStateDefinitionTransformer = $shopReadinessStateDefinitionTransformer;
        $this->shopReadinessStateDefinitionsTransformer = $shopReadinessStateDefinitionsTransformer;
        $this->createShopReadinessStateDefinitionRequestSerializer = $createShopReadinessStateDefinitionRequestSerializer;
        $this->updateShopReadinessStateDefinitionRequestSerializer = $updateShopReadinessStateDefinitionRequestSerializer;
        $this->definitionCache = $definitionCache;
        $this->cache = $cache;
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
        $this->cache->clear();

        return $shopReadinessStateDefinition;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $readinessStateDefinitionId): void
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $readinessStateDefinitionId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->cache->clear();
        $this->definitionCache->delete((string) $readinessStateDefinitionId);
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShopReadinessStateDefinitionInterface>
     */
    public function getMultiple(bool $skipCache = false, ?int $limit = null, ?int $offset = null): array
    {
        $cacheKey = sprintf('all:%s:%s', $limit ?? '', $offset ?? '');
        if (!$skipCache) {
            if ($this->cache->has($cacheKey)) {
                /**
                 * @var array<int, ShopReadinessStateDefinitionInterface> $cached
                 */
                $cached = $this->cache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildPaginationQuery($limit, $offset), $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $shopReadinessStateDefinitions = $this->shopReadinessStateDefinitionsTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache->set($cacheKey, $shopReadinessStateDefinitions);

        return $shopReadinessStateDefinitions;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $readinessStateDefinitionId, bool $skipCache = false): ShopReadinessStateDefinitionInterface
    {
        if (!$skipCache) {
            if ($this->definitionCache->has((string) $readinessStateDefinitionId)) {
                /**
                 * @var ShopReadinessStateDefinitionInterface $cached
                 */
                $cached = $this->definitionCache->get((string) $readinessStateDefinitionId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $readinessStateDefinitionId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopReadinessStateDefinition = $this->shopReadinessStateDefinitionTransformer->transform($data);
        $this->definitionCache->set((string) $readinessStateDefinitionId, $shopReadinessStateDefinition);

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
        $this->cache->clear();
        $this->definitionCache->set((string) $readinessStateDefinitionId, $shopReadinessStateDefinition);

        return $shopReadinessStateDefinition;
    }

    /**
     * @return array<string, string>
     */
    private static function buildPaginationQuery(?int $limit, ?int $offset): array
    {
        $query = [];
        if (null !== $limit) {
            $query[self::KEY_LIMIT] = (string) $limit;
        }
        if (null !== $offset) {
            $query[self::KEY_OFFSET] = (string) $offset;
        }

        return $query;
    }
}
