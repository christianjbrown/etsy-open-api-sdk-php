<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Endpoint;

use ChristianBrown\Etsy\Endpoint\ReceiptsApi;
use ChristianBrown\Etsy\Endpoint\ReceiptsApiInterface;
use ChristianBrown\Etsy\Endpoint\ResultSetBasedApiInterface;
use ChristianBrown\Etsy\Model\ResultSetInterface;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReceiptsApi::class)]
final class ReceiptsApiTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetResultSet(): void
    {
        $resultSet = $this->createMock(ResultSetInterface::class);

        $receiptsTransformer = $this->createMock(ReceiptsTransformerInterface::class);

        $resultSetApi = $this->createMock(ResultSetBasedApiInterface::class);
        $resultSetApi->method('fetchResultSet')
            ->with(ReceiptsApiInterface::URL, $receiptsTransformer, 42, 59, [])
            ->willReturn($resultSet);

        $api = new ReceiptsApi($resultSetApi, $receiptsTransformer);
        $actual = $api->getResultSet(42, 59);

        self::assertSame($resultSet, $actual);
    }
}
