<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
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

    /**
     * @var null|array<int, BuyerTaxonomyNodeInterface>
     */
    private ?array $nodesCache = null;

    /**
     * @var array<int, array<int, BuyerTaxonomyNodePropertyInterface>>
     */
    private array $propertiesCache = [];
    private JsonApiRequestSenderInterface $requestSender;

    public function __construct(JsonApiRequestSenderInterface $requestSender, BuyerTaxonomyNodesTransformerInterface $buyerTaxonomyNodesTransformer, BuyerTaxonomyNodePropertiesTransformerInterface $buyerTaxonomyNodePropertiesTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->buyerTaxonomyNodesTransformer = $buyerTaxonomyNodesTransformer;
        $this->buyerTaxonomyNodePropertiesTransformer = $buyerTaxonomyNodePropertiesTransformer;
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
        $nodes = $this->buyerTaxonomyNodesTransformer->transform($data[self::KEY_RESULTS]);
        $this->nodesCache = $nodes;

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
        $properties = $this->buyerTaxonomyNodePropertiesTransformer->transform($data[self::KEY_RESULTS]);
        $this->propertiesCache[$taxonomyId] = $properties;

        return $properties;
    }
}
