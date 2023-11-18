<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ResultSetInterface;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;

final class ReceiptsApi extends AbstractApi implements ReceiptsApiInterface
{
    private ReceiptsTransformer $receiptsTransformer;

    public function __construct(int $shopId, string $key, KeyValueStoreInterface $refreshTokenKeyValueStore, ?KeyValueStoreInterface $accessTokenKeyValueStore = null)
    {
        parent::__construct($shopId, $key, $refreshTokenKeyValueStore, $accessTokenKeyValueStore);
        $this->receiptsTransformer = new ReceiptsTransformer();
    }

    public function get(int $offset = 0, int $limit = self::DEFAULT_LIMIT): ResultSetInterface
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
