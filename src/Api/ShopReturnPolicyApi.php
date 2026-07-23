<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPoliciesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPolicyTransformerInterface;

use function is_array;
use function sprintf;

final class ShopReturnPolicyApi implements ShopReturnPolicyApiInterface
{
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
    private ShopReturnPolicyTransformerInterface $shopReturnPolicyTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ShopReturnPolicyTransformerInterface $shopReturnPolicyTransformer, ShopReturnPoliciesTransformerInterface $shopReturnPoliciesTransformer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->shopReturnPolicyTransformer = $shopReturnPolicyTransformer;
        $this->shopReturnPoliciesTransformer = $shopReturnPoliciesTransformer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
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
}
