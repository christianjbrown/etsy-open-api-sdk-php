<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonReadApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodeInterface;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodePropertyInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertiesTransformerInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodesTransformerInterface;

use function is_array;
use function sprintf;

final class BuyerTaxonomyApi implements BuyerTaxonomyApiInterface
{
    private BuyerTaxonomyNodePropertiesTransformerInterface $buyerTaxonomyNodePropertiesTransformer;
    private BuyerTaxonomyNodesTransformerInterface $buyerTaxonomyNodesTransformer;
    private CredentialsInterface $credentials;
    private ResponseCacheInterface $nodesCache;
    private ResponseCacheInterface $propertiesCache;
    private JsonReadApiRequestSenderInterface $requestSender;

    public function __construct(JsonReadApiRequestSenderInterface $requestSender, BuyerTaxonomyNodesTransformerInterface $buyerTaxonomyNodesTransformer, BuyerTaxonomyNodePropertiesTransformerInterface $buyerTaxonomyNodePropertiesTransformer, ResponseCacheInterface $propertiesCache, ResponseCacheInterface $nodesCache, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->buyerTaxonomyNodesTransformer = $buyerTaxonomyNodesTransformer;
        $this->buyerTaxonomyNodePropertiesTransformer = $buyerTaxonomyNodePropertiesTransformer;
        $this->propertiesCache = $propertiesCache;
        $this->nodesCache = $nodesCache;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, BuyerTaxonomyNodeInterface>
     */
    public function getNodes(bool $skipCache = false): array
    {
        if (!$skipCache) {
            if ($this->nodesCache->has('all')) {
                /**
                 * @var array<int, BuyerTaxonomyNodeInterface> $cached
                 */
                $cached = $this->nodesCache->get('all');

                return $cached;
            }
        }

        $data = $this->requestSender->get(self::API_URL_NODES, [], $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $nodes = $this->buyerTaxonomyNodesTransformer->transform($data[self::KEY_RESULTS]);
        $this->nodesCache->set('all', $nodes);

        return $nodes;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, BuyerTaxonomyNodePropertyInterface>
     */
    public function getProperties(int $taxonomyId, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if ($this->propertiesCache->has((string) $taxonomyId)) {
                /**
                 * @var array<int, BuyerTaxonomyNodePropertyInterface> $cached
                 */
                $cached = $this->propertiesCache->get((string) $taxonomyId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_PROPERTIES_SPRINTF, $taxonomyId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $properties = $this->buyerTaxonomyNodePropertiesTransformer->transform($data[self::KEY_RESULTS]);
        $this->propertiesCache->set((string) $taxonomyId, $properties);

        return $properties;
    }
}
