<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ResultSet;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;

final class ReceiptsApi extends AbstractApi
{
    private const DEFAULT_LIMIT = 100;
    private const URL = 'https://openapi.etsy.com/v3/application/shops/%d/receipts';

    private ReceiptsTransformer $receiptsTransformer;

    public function __construct(int $shopId, string $key, KeyValueStoreInterface $accessTokenKeyValueStore, KeyValueStoreInterface $refreshTokenKeyValueStore)
    {
        parent::__construct($shopId, $key, $accessTokenKeyValueStore, $refreshTokenKeyValueStore);
        $this->receiptsTransformer = new ReceiptsTransformer();
    }

    public function get(int $offset = 0, int $limit = self::DEFAULT_LIMIT): ResultSet
    {
        $url = sprintf(self::URL, $this->shopId);
        $queryStrings = [
            self::API_PARAM_LIMIT => $limit,
            self::API_PARAM_OFFSET => $offset,
        ];
        $data = $this->requestSender->get($url, $queryStrings);
        $resultSet = $this->resultSetTransformer->transform($data, $this->receiptsTransformer);

        return $resultSet;
    }
}
