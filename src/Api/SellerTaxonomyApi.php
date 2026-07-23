<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
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

    /**
     * @var null|array<int, SellerTaxonomyNodeInterface>
     */
    private ?array $nodesCache = null;

    /**
     * @var array<int, array<int, TaxonomyNodePropertyInterface>>
     */
    private array $propertiesCache = [];
    private JsonApiRequestSenderInterface $requestSender;
    private SellerTaxonomyNodesTransformerInterface $sellerTaxonomyNodesTransformer;
    private TaxonomyNodePropertiesTransformerInterface $taxonomyNodePropertiesTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, SellerTaxonomyNodesTransformerInterface $sellerTaxonomyNodesTransformer, TaxonomyNodePropertiesTransformerInterface $taxonomyNodePropertiesTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->sellerTaxonomyNodesTransformer = $sellerTaxonomyNodesTransformer;
        $this->taxonomyNodePropertiesTransformer = $taxonomyNodePropertiesTransformer;
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
            if (null !== $this->nodesCache) {
                return $this->nodesCache;
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
        $this->nodesCache = $nodes;

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
            if (isset($this->propertiesCache[$taxonomyId])) {
                return $this->propertiesCache[$taxonomyId];
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
        $this->propertiesCache[$taxonomyId] = $properties;

        return $properties;
    }
}
