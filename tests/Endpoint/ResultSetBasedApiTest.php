<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Endpoint;

use ChristianBrown\Etsy\Endpoint\ResultSetBasedApi;
use ChristianBrown\Etsy\Endpoint\ResultSetBasedApiInterface;
use ChristianBrown\Etsy\Model\ResultSetInterface;
use ChristianBrown\Etsy\Request\ApiConnectorInterface;
use ChristianBrown\Etsy\Transformer\ObjectsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ResultSetTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResultSetBasedApi::class)]
final class ResultSetBasedApiTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetResultSet(): void
    {
        $apiConnector = $this->createMock(ApiConnectorInterface::class);
        $apiConnector->method('get')
            ->with('test-url', ['test-key' => 'test-value', ResultSetBasedApiInterface::API_PARAM_LIMIT => 75, ResultSetBasedApiInterface::API_PARAM_OFFSET => 3])
            ->willReturn(['test-response-data']);

        $objectsTransformer = $this->createMock(ObjectsTransformerInterface::class);

        $resultSet = $this->createMock(ResultSetInterface::class);

        $resultSetTransformer = $this->createMock(ResultSetTransformerInterface::class);
        $resultSetTransformer->method('transform')
            ->with(
                ['test-response-data'],
                $objectsTransformer
            )
            ->willReturn($resultSet);

        $api = new ResultSetBasedApi($apiConnector, $resultSetTransformer, 123);
        $actual = $api->fetchResultSet('test-url', $objectsTransformer, 3, 75, ['test-key' => 'test-value']);

        self::assertSame($resultSet, $actual);
    }
}
