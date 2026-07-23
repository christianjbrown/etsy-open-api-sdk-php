<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\SellerTaxonomyApi;
use ChristianBrown\Etsy\Api\SellerTaxonomyApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\SellerTaxonomyNodeInterface;
use ChristianBrown\Etsy\Model\TaxonomyNodePropertyInterface;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodesTransformerInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertiesTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(SellerTaxonomyApi::class)]
final class SellerTaxonomyApiTest extends TestCase
{
    private const int TAXONOMY_ID = 77;

    public function testGetNodesReturnsNodes(): void
    {
        $resultsData = [['node-1'], ['node-2']];
        $headers = ['x-api-key' => 'key'];
        $nodes = [self::createStub(SellerTaxonomyNodeInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(SellerTaxonomyApiInterface::API_URL_NODES, [], $headers)
            ->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => $resultsData]);

        $nodesTransformer = self::createMock(SellerTaxonomyNodesTransformerInterface::class);
        $nodesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($nodes);

        $api = $this->buildApi($headers, $requestSender, $nodesTransformer, self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        self::assertSame($nodes, $api->getNodes());
    }

    public function testGetNodesSkipCacheRefetches(): void
    {
        $nodes = [self::createStub(SellerTaxonomyNodeInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => [['node-1']]]);

        $nodesTransformer = self::createStub(SellerTaxonomyNodesTransformerInterface::class);
        $nodesTransformer->method('transform')->willReturn($nodes);

        $api = $this->buildApi([], $requestSender, $nodesTransformer, self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        self::assertSame($nodes, $api->getNodes(true));
        self::assertSame($nodes, $api->getNodes(true));
    }

    public function testGetNodesSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(SellerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, SellerTaxonomyApiInterface::KEY_RESULTS));

        $api->getNodes(true);
    }

    public function testGetNodesSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(SellerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, SellerTaxonomyApiInterface::KEY_RESULTS));

        $api->getNodes(true);
    }

    public function testGetNodesThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(SellerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, SellerTaxonomyApiInterface::KEY_RESULTS));

        $api->getNodes();
    }

    public function testGetNodesThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(SellerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, SellerTaxonomyApiInterface::KEY_RESULTS));

        $api->getNodes();
    }

    public function testGetNodesUsesCacheOnSecondCall(): void
    {
        $nodes = [self::createStub(SellerTaxonomyNodeInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => [['node-1']]]);

        $nodesTransformer = self::createStub(SellerTaxonomyNodesTransformerInterface::class);
        $nodesTransformer->method('transform')->willReturn($nodes);

        $api = $this->buildApi([], $requestSender, $nodesTransformer, self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        self::assertSame($nodes, $api->getNodes());
        self::assertSame($nodes, $api->getNodes());
    }

    public function testGetPropertiesReturnsProperties(): void
    {
        $resultsData = [['property-1'], ['property-2']];
        $headers = ['x-api-key' => 'key'];
        $properties = [self::createStub(TaxonomyNodePropertyInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(sprintf(SellerTaxonomyApiInterface::API_URL_PROPERTIES_SPRINTF, self::TAXONOMY_ID), [], $headers)
            ->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => $resultsData]);

        $propertiesTransformer = self::createMock(TaxonomyNodePropertiesTransformerInterface::class);
        $propertiesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($properties);

        $api = $this->buildApi($headers, $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), $propertiesTransformer);

        self::assertSame($properties, $api->getProperties(self::TAXONOMY_ID));
    }

    public function testGetPropertiesSkipCacheRefetches(): void
    {
        $properties = [self::createStub(TaxonomyNodePropertyInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => [['property-1']]]);

        $propertiesTransformer = self::createStub(TaxonomyNodePropertiesTransformerInterface::class);
        $propertiesTransformer->method('transform')->willReturn($properties);

        $api = $this->buildApi([], $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), $propertiesTransformer);

        self::assertSame($properties, $api->getProperties(self::TAXONOMY_ID, true));
        self::assertSame($properties, $api->getProperties(self::TAXONOMY_ID, true));
    }

    public function testGetPropertiesSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(SellerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, SellerTaxonomyApiInterface::KEY_RESULTS));

        $api->getProperties(self::TAXONOMY_ID, true);
    }

    public function testGetPropertiesSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(SellerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, SellerTaxonomyApiInterface::KEY_RESULTS));

        $api->getProperties(self::TAXONOMY_ID, true);
    }

    public function testGetPropertiesThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(SellerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, SellerTaxonomyApiInterface::KEY_RESULTS));

        $api->getProperties(self::TAXONOMY_ID);
    }

    public function testGetPropertiesThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), self::createStub(TaxonomyNodePropertiesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(SellerTaxonomyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, SellerTaxonomyApiInterface::KEY_RESULTS));

        $api->getProperties(self::TAXONOMY_ID);
    }

    public function testGetPropertiesUsesCacheOnSecondCall(): void
    {
        $properties = [self::createStub(TaxonomyNodePropertyInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([SellerTaxonomyApiInterface::KEY_RESULTS => [['property-1']]]);

        $propertiesTransformer = self::createStub(TaxonomyNodePropertiesTransformerInterface::class);
        $propertiesTransformer->method('transform')->willReturn($properties);

        $api = $this->buildApi([], $requestSender, self::createStub(SellerTaxonomyNodesTransformerInterface::class), $propertiesTransformer);

        self::assertSame($properties, $api->getProperties(self::TAXONOMY_ID));
        self::assertSame($properties, $api->getProperties(self::TAXONOMY_ID));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(
        array $headers,
        JsonApiRequestSenderInterface $requestSender,
        SellerTaxonomyNodesTransformerInterface $nodesTransformer,
        TaxonomyNodePropertiesTransformerInterface $propertiesTransformer,
    ): SellerTaxonomyApi {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new SellerTaxonomyApi($requestSender, $nodesTransformer, $propertiesTransformer, $credentials);
    }
}
