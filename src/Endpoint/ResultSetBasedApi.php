<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Endpoint;

use ChristianBrown\Etsy\Model\ResultSetInterface;
use ChristianBrown\Etsy\Request\ApiConnectorInterface;
use ChristianBrown\Etsy\Transformer\ObjectsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ResultSetTransformerInterface;

final class ResultSetBasedApi implements ResultSetBasedApiInterface
{
    private ApiConnectorInterface $apiConnector;
    private ResultSetTransformerInterface $resultSetTransformer;
    private int $shopId;

    public function __construct(ApiConnectorInterface $apiConnector, ResultSetTransformerInterface $resultSetTransformer, int $shopId)
    {
        $this->apiConnector = $apiConnector;
        $this->resultSetTransformer = $resultSetTransformer;
        $this->shopId = $shopId;
    }

    public function fetchResultSet(string $urlSprintF, ObjectsTransformerInterface $objectsTransformer, int $offset = 0, int $limit = self::DEFAULT_LIMIT, array $query = []): ResultSetInterface
    {
        $url = sprintf($urlSprintF, $this->shopId);
        $query[self::API_PARAM_LIMIT] = $limit;
        $query[self::API_PARAM_OFFSET] = $offset;

        $data = $this->apiConnector->get($url, $query);
        $resultSet = $this->resultSetTransformer->transform($data, $objectsTransformer);

        return $resultSet;
    }
}
