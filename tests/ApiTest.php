<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests;

use ChristianBrown\Etsy\Api;
use ChristianBrown\Etsy\Endpoint\ReceiptsApi;
use ChristianBrown\Etsy\Endpoint\ReceiptsApiInterface;
use ChristianBrown\Etsy\Endpoint\ResultSetBasedApi;
use ChristianBrown\Etsy\Request\ApiConnector;
use ChristianBrown\Etsy\Request\AuthenticationManager;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[CoversClass(Api::class)]
#[CoversClass(ReceiptsApi::class)]
#[CoversClass(ResultSetBasedApi::class)]
#[CoversClass(ApiConnector::class)]
#[CoversClass(AuthenticationManager::class)]
#[CoversClass(ReceiptTransformer::class)]
#[CoversClass(ReceiptsTransformer::class)]
#[CoversClass(TransactionTransformer::class)]
#[CoversClass(TransactionsTransformer::class)]
final class ApiTest extends TestCase
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testGetReceiptsApi(): void
    {
        $accessTokenStore = $this->createMock(KeyValueStoreInterface::class);
        $refreshTokenStore = $this->createMock(KeyValueStoreInterface::class);
        $api = new Api(123, 'test-key', $accessTokenStore, $refreshTokenStore);
        $receiptsApi = $api->getReceiptsApi();
        self::assertInstanceOf(ReceiptsApiInterface::class, $receiptsApi);
    }
}
