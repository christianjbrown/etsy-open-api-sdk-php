<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReadinessStateDefinitionInterface;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionTransformerInterface;

use function is_array;
use function sprintf;

final class ShopReadinessStateDefinitionApi implements ShopReadinessStateDefinitionApiInterface
{
    /**
     * @var null|array<int, ShopReadinessStateDefinitionInterface>
     */
    private ?array $cache = null;
    private CredentialsInterface $credentials;

    /**
     * @var array<int, ShopReadinessStateDefinitionInterface>
     */
    private array $definitionCache = [];
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private ShopReadinessStateDefinitionsTransformerInterface $shopReadinessStateDefinitionsTransformer;
    private ShopReadinessStateDefinitionTransformerInterface $shopReadinessStateDefinitionTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ShopReadinessStateDefinitionTransformerInterface $shopReadinessStateDefinitionTransformer, ShopReadinessStateDefinitionsTransformerInterface $shopReadinessStateDefinitionsTransformer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->shopReadinessStateDefinitionTransformer = $shopReadinessStateDefinitionTransformer;
        $this->shopReadinessStateDefinitionsTransformer = $shopReadinessStateDefinitionsTransformer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
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
}
