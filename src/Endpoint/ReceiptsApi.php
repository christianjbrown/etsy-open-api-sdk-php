<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Endpoint;

use ChristianBrown\Etsy\Model\ResultSetInterface;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformerInterface;

final class ReceiptsApi implements ReceiptsApiInterface
{
    private ReceiptsTransformerInterface $receiptsTransformer;
    private ResultSetBasedApiInterface $resultSetApi;

    public function __construct(ResultSetBasedApiInterface $resultSetApi, ReceiptsTransformerInterface $receiptsTransformer)
    {
        $this->resultSetApi = $resultSetApi;
        $this->receiptsTransformer = $receiptsTransformer;
    }

    public function getResultSet(int $offset = 0, int $limit = ResultSetBasedApiInterface::DEFAULT_LIMIT): ResultSetInterface
    {
        $resultSet = $this->resultSetApi->fetchResultSet(self::URL, $this->receiptsTransformer, $offset, $limit);

        return $resultSet;
    }
}
