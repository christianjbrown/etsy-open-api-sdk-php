<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonReadApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\SellerTaxonomyNodeInterface;
use ChristianBrown\Etsy\Model\TaxonomyNodePropertyInterface;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodesTransformerInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertiesTransformerInterface;

use function is_array;
use function sprintf;

final class SellerTaxonomyApi implements SellerTaxonomyApiInterface
{
    private CredentialsInterface $credentials;
    private ResponseCacheInterface $nodesCache;
    private ResponseCacheInterface $propertiesCache;
    private JsonReadApiRequestSenderInterface $requestSender;
    private SellerTaxonomyNodesTransformerInterface $sellerTaxonomyNodesTransformer;
    private TaxonomyNodePropertiesTransformerInterface $taxonomyNodePropertiesTransformer;

    public function __construct(JsonReadApiRequestSenderInterface $requestSender, SellerTaxonomyNodesTransformerInterface $sellerTaxonomyNodesTransformer, TaxonomyNodePropertiesTransformerInterface $taxonomyNodePropertiesTransformer, ResponseCacheInterface $propertiesCache, ResponseCacheInterface $nodesCache, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->sellerTaxonomyNodesTransformer = $sellerTaxonomyNodesTransformer;
        $this->taxonomyNodePropertiesTransformer = $taxonomyNodePropertiesTransformer;
        $this->propertiesCache = $propertiesCache;
        $this->nodesCache = $nodesCache;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, SellerTaxonomyNodeInterface>
     */
    public function getNodes(bool $skipCache = false): array
    {
        if (!$skipCache) {
            if ($this->nodesCache->has('all')) {
                /**
                 * @var array<int, SellerTaxonomyNodeInterface> $cached
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
        $nodes = $this->sellerTaxonomyNodesTransformer->transform($data[self::KEY_RESULTS]);
        $this->nodesCache->set('all', $nodes);

        return $nodes;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, TaxonomyNodePropertyInterface>
     */
    public function getProperties(int $taxonomyId, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if ($this->propertiesCache->has((string) $taxonomyId)) {
                /**
                 * @var array<int, TaxonomyNodePropertyInterface> $cached
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
        $properties = $this->taxonomyNodePropertiesTransformer->transform($data[self::KEY_RESULTS]);
        $this->propertiesCache->set((string) $taxonomyId, $properties);

        return $properties;
    }
}
