<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;
use ChristianBrown\Etsy\Model\ShopReturnPolicyRequestInterface;
use ChristianBrown\Etsy\Serializer\ShopReturnPolicyRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPoliciesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPolicyTransformerInterface;

use function is_array;
use function sprintf;

final class ShopReturnPolicyApi implements ShopReturnPolicyApiInterface
{
    private ApiRequestSenderInterface $apiRequestSender;

    /**
     * @var null|array<int, ShopReturnPolicyInterface>
     */
    private ?array $cache = null;
    private CredentialsInterface $credentials;
    private JsonApiRequestSenderInterface $requestSender;

    /**
     * @var array<int, ShopReturnPolicyInterface>
     */
    private array $returnPolicyCache = [];
    private int $shopId;
    private ShopReturnPoliciesTransformerInterface $shopReturnPoliciesTransformer;
    private ShopReturnPolicyRequestSerializerInterface $shopReturnPolicyRequestSerializer;
    private ShopReturnPolicyTransformerInterface $shopReturnPolicyTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ShopReturnPolicyTransformerInterface $shopReturnPolicyTransformer, ShopReturnPoliciesTransformerInterface $shopReturnPoliciesTransformer, ShopReturnPolicyRequestSerializerInterface $shopReturnPolicyRequestSerializer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->shopReturnPolicyTransformer = $shopReturnPolicyTransformer;
        $this->shopReturnPoliciesTransformer = $shopReturnPoliciesTransformer;
        $this->shopReturnPolicyRequestSerializer = $shopReturnPolicyRequestSerializer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function consolidate(int $sourceReturnPolicyId, int $destinationReturnPolicyId): ShopReturnPolicyInterface
    {
        $url = sprintf(self::API_URL_CONSOLIDATE_SPRINTF, $this->shopId);
        $body = [
            self::KEY_SOURCE_RETURN_POLICY_ID => (string) $sourceReturnPolicyId,
            self::KEY_DESTINATION_RETURN_POLICY_ID => (string) $destinationReturnPolicyId,
        ];
        $data = $this->requestSender->postForm($url, [], $this->credentials->toHeaders(), $body);

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopReturnPolicy = $this->shopReturnPolicyTransformer->transform($data);
        $this->cache = null;
        unset($this->returnPolicyCache[$sourceReturnPolicyId]);
        $this->returnPolicyCache[$destinationReturnPolicyId] = $shopReturnPolicy;

        return $shopReturnPolicy;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function create(ShopReturnPolicyRequestInterface $shopReturnPolicyRequest): ShopReturnPolicyInterface
    {
        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->postForm($url, [], $this->credentials->toHeaders(), $this->shopReturnPolicyRequestSerializer->serialize($shopReturnPolicyRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopReturnPolicy = $this->shopReturnPolicyTransformer->transform($data);
        $this->cache = null;

        return $shopReturnPolicy;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $returnPolicyId): void
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $returnPolicyId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->cache = null;
        unset($this->returnPolicyCache[$returnPolicyId]);
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShopReturnPolicyInterface>
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
        $shopReturnPolicies = $this->shopReturnPoliciesTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache = $shopReturnPolicies;

        return $shopReturnPolicies;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $returnPolicyId, bool $skipCache = false): ShopReturnPolicyInterface
    {
        if (!$skipCache) {
            if (isset($this->returnPolicyCache[$returnPolicyId])) {
                return $this->returnPolicyCache[$returnPolicyId];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $returnPolicyId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopReturnPolicy = $this->shopReturnPolicyTransformer->transform($data);
        $this->returnPolicyCache[$returnPolicyId] = $shopReturnPolicy;

        return $shopReturnPolicy;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function update(int $returnPolicyId, ShopReturnPolicyRequestInterface $shopReturnPolicyRequest): ShopReturnPolicyInterface
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $returnPolicyId);
        $data = $this->requestSender->putForm($url, [], $this->credentials->toHeaders(), $this->shopReturnPolicyRequestSerializer->serialize($shopReturnPolicyRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopReturnPolicy = $this->shopReturnPolicyTransformer->transform($data);
        $this->cache = null;
        $this->returnPolicyCache[$returnPolicyId] = $shopReturnPolicy;

        return $shopReturnPolicy;
    }
}
