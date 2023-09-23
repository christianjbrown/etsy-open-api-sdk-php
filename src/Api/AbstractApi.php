<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Api\Request\EtsyApiRequestSender;
use ChristianBrown\Etsy\Api\Request\RequestSender;
use ChristianBrown\Etsy\Transformer\ResultSetTransformer;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;

abstract class AbstractApi
{
    protected const API_PARAM_LIMIT = 'limit';
    protected const API_PARAM_OFFSET = 'offset';
    protected RequestSender $requestSender;
    protected ResultSetTransformer $resultSetTransformer;

    protected int $shopId;

    public function __construct(int $shopId, string $key, KeyValueStoreInterface $accessTokenKeyValueStore, KeyValueStoreInterface $refreshTokenKeyValueStore)
    {
        $this->requestSender = new RequestSender($key, $accessTokenKeyValueStore, $refreshTokenKeyValueStore);
        $this->shopId = $shopId;
        $this->resultSetTransformer = new ResultSetTransformer();
    }
}
