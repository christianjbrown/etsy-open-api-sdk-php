<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\BuyerTaxonomyApi;
use ChristianBrown\Etsy\Api\BuyerTaxonomyApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodeInterface;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodePropertyInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertiesTransformerInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodesTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(BuyerTaxonomyApi::class)]
final class BuyerTaxonomyApiTest extends TestCase
{
    private const int TAXONOMY_ID = 77;

    public function testGetNodesReturnsNodes(): void
    {
        $resultsData = [['node-1'], ['node-2']];
        $headers = ['x-api-key' => 'key'];
        $nodes = [self::createStub(BuyerTaxonomyNodeInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(BuyerTaxonomyApiInterface::API_URL_NODES, [], $headers)
            ->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => $resultsData]);

        $nodesTransformer = self::createMock(BuyerTaxonomyNodesTransformerInterface::class);
        $nodesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($nodes);

        $api = $this->buildApi($headers, $requestSender, $nodesTransformer, self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        self::assertSame($nodes, $api->getNodes());
    }

    public function testGetNodesSkipCacheRefetches(): void
    {
        $nodes = [self::createStub(BuyerTaxonomyNodeInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => [['node-1']]]);

        $nodesTransformer = self::createStub(BuyerTaxonomyNodesTransformerInterface::class);
        $nodesTransformer->method('transform')->willReturn($nodes);

        $api = $this->buildApi([], $requestSender, $nodesTransformer, self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        $first = $api->getNodes(true);
        $second = $api->getNodes(true);

        self::assertSame($nodes, $first);
        self::assertSame($nodes, $second);
    }

    public function testGetNodesSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, BuyerTaxonomyApiInterface::KEY_RESULTS));

        $api->getNodes(true);
    }

    public function testGetNodesSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, BuyerTaxonomyApiInterface::KEY_RESULTS));

        $api->getNodes(true);
    }

    public function testGetNodesThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, BuyerTaxonomyApiInterface::KEY_RESULTS));

        $api->getNodes();
    }

    public function testGetNodesThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, BuyerTaxonomyApiInterface::KEY_RESULTS));

        $api->getNodes();
    }

    public function testGetNodesUsesCacheOnSecondCall(): void
    {
        $nodes = [self::createStub(BuyerTaxonomyNodeInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => [['node-1']]]);

        $nodesTransformer = self::createStub(BuyerTaxonomyNodesTransformerInterface::class);
        $nodesTransformer->method('transform')->willReturn($nodes);

        $api = $this->buildApi([], $requestSender, $nodesTransformer, self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        $first = $api->getNodes();
        $second = $api->getNodes();

        self::assertSame($nodes, $first);
        self::assertSame($nodes, $second);
    }

    public function testGetPropertiesReturnsProperties(): void
    {
        $resultsData = [['property-1'], ['property-2']];
        $headers = ['x-api-key' => 'key'];
        $properties = [self::createStub(BuyerTaxonomyNodePropertyInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(sprintf(BuyerTaxonomyApiInterface::API_URL_PROPERTIES_SPRINTF, self::TAXONOMY_ID), [], $headers)
            ->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => $resultsData]);

        $propertiesTransformer = self::createMock(BuyerTaxonomyNodePropertiesTransformerInterface::class);
        $propertiesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($properties);

        $api = $this->buildApi($headers, $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), $propertiesTransformer);

        self::assertSame($properties, $api->getProperties(self::TAXONOMY_ID));
    }

    public function testGetPropertiesSkipCacheRefetches(): void
    {
        $properties = [self::createStub(BuyerTaxonomyNodePropertyInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => [['property-1']]]);

        $propertiesTransformer = self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class);
        $propertiesTransformer->method('transform')->willReturn($properties);

        $api = $this->buildApi([], $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), $propertiesTransformer);

        $first = $api->getProperties(self::TAXONOMY_ID, true);
        $second = $api->getProperties(self::TAXONOMY_ID, true);

        self::assertSame($properties, $first);
        self::assertSame($properties, $second);
    }

    public function testGetPropertiesSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, BuyerTaxonomyApiInterface::KEY_RESULTS));

        $api->getProperties(self::TAXONOMY_ID, true);
    }

    public function testGetPropertiesSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, BuyerTaxonomyApiInterface::KEY_RESULTS));

        $api->getProperties(self::TAXONOMY_ID, true);
    }

    public function testGetPropertiesThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, BuyerTaxonomyApiInterface::KEY_RESULTS));

        $api->getProperties(self::TAXONOMY_ID);
    }

    public function testGetPropertiesThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, BuyerTaxonomyApiInterface::KEY_RESULTS));

        $api->getProperties(self::TAXONOMY_ID);
    }

    public function testGetPropertiesUsesCacheOnSecondCall(): void
    {
        $properties = [self::createStub(BuyerTaxonomyNodePropertyInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([BuyerTaxonomyApiInterface::KEY_RESULTS => [['property-1']]]);

        $propertiesTransformer = self::createStub(BuyerTaxonomyNodePropertiesTransformerInterface::class);
        $propertiesTransformer->method('transform')->willReturn($properties);

        $api = $this->buildApi([], $requestSender, self::createStub(BuyerTaxonomyNodesTransformerInterface::class), $propertiesTransformer);

        $first = $api->getProperties(self::TAXONOMY_ID);
        $second = $api->getProperties(self::TAXONOMY_ID);

        self::assertSame($properties, $first);
        self::assertSame($properties, $second);
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(
        array $headers,
        JsonApiRequestSenderInterface $requestSender,
        BuyerTaxonomyNodesTransformerInterface $nodesTransformer,
        BuyerTaxonomyNodePropertiesTransformerInterface $propertiesTransformer,
    ): BuyerTaxonomyApi {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new BuyerTaxonomyApi($requestSender, $nodesTransformer, $propertiesTransformer, $credentials);
    }
}
