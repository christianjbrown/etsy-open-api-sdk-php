<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopReadinessStateDefinitionApi;
use ChristianBrown\Etsy\Api\ShopReadinessStateDefinitionApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\CreateShopReadinessStateDefinitionRequestInterface;
use ChristianBrown\Etsy\Model\ShopReadinessStateDefinitionInterface;
use ChristianBrown\Etsy\Model\UpdateShopReadinessStateDefinitionRequestInterface;
use ChristianBrown\Etsy\Serializer\CreateShopReadinessStateDefinitionRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopReadinessStateDefinitionRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopReadinessStateDefinitionApi::class)]
final class ShopReadinessStateDefinitionApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testCreateReturnsDefinition(): void
    {
        $headers = ['x-api-key' => 'key'];
        $definitionData = ['definition-self'];
        $definition = self::createStub(ShopReadinessStateDefinitionInterface::class);
        $serializedBody = ['readiness_state' => 'processed'];

        $createRequest = self::createStub(CreateShopReadinessStateDefinitionRequestInterface::class);
        $createRequestSerializer = self::createMock(CreateShopReadinessStateDefinitionRequestSerializerInterface::class);
        $createRequestSerializer->expects(self::once())->method('serialize')
            ->with($createRequest)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('postForm')
            ->with(
                sprintf(ShopReadinessStateDefinitionApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($definitionData);

        $definitionTransformer = self::createMock(ShopReadinessStateDefinitionTransformerInterface::class);
        $definitionTransformer->expects(self::once())->method('transform')
            ->with($definitionData)
            ->willReturn($definition);

        $api = $this->buildApi($headers, $requestSender, $definitionTransformer, self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class), null, $createRequestSerializer);

        self::assertSame($definition, $api->create($createRequest));
    }

    public function testCreateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('postForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReadinessStateDefinitionApiInterface::UNEXPECTED_RESPONSE);

        $api->create(self::createStub(CreateShopReadinessStateDefinitionRequestInterface::class));
    }

    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ShopReadinessStateDefinitionApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 77),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class), $apiRequestSender);

        $api->delete(77);
    }

    public function testGetMultipleReturnsDefinitions(): void
    {
        $resultsData = [['definition-1'], ['definition-2']];
        $headers = ['x-api-key' => 'key'];
        $definitions = [self::createStub(ShopReadinessStateDefinitionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopReadinessStateDefinitionApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
            )
            ->willReturn([ShopReadinessStateDefinitionApiInterface::KEY_RESULTS => $resultsData]);

        $definitionsTransformer = self::createMock(ShopReadinessStateDefinitionsTransformerInterface::class);
        $definitionsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($definitions);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), $definitionsTransformer);

        self::assertSame($definitions, $api->getMultiple());
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $definitions = [self::createStub(ShopReadinessStateDefinitionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopReadinessStateDefinitionApiInterface::KEY_RESULTS => [['definition-1']]]);

        $definitionsTransformer = self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class);
        $definitionsTransformer->method('transform')->willReturn($definitions);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), $definitionsTransformer);

        self::assertSame($definitions, $api->getMultiple(true));
        self::assertSame($definitions, $api->getMultiple(true));
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReadinessStateDefinitionApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReadinessStateDefinitionApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReadinessStateDefinitionApiInterface::KEY_RESULTS));

        $api->getMultiple(true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReadinessStateDefinitionApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReadinessStateDefinitionApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReadinessStateDefinitionApiInterface::KEY_RESULTS));

        $api->getMultiple(true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReadinessStateDefinitionApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReadinessStateDefinitionApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReadinessStateDefinitionApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReadinessStateDefinitionApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReadinessStateDefinitionApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReadinessStateDefinitionApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $definitions = [self::createStub(ShopReadinessStateDefinitionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopReadinessStateDefinitionApiInterface::KEY_RESULTS => [['definition-1']]]);

        $definitionsTransformer = self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class);
        $definitionsTransformer->method('transform')->willReturn($definitions);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), $definitionsTransformer);

        self::assertSame($definitions, $api->getMultiple());
        self::assertSame($definitions, $api->getMultiple());
    }

    public function testGetOneByIdReturnsDefinition(): void
    {
        $definitionData = ['definition-self'];
        $headers = ['x-api-key' => 'key'];
        $definition = self::createStub(ShopReadinessStateDefinitionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopReadinessStateDefinitionApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 77),
                [],
                $headers,
            )
            ->willReturn($definitionData);

        $definitionTransformer = self::createMock(ShopReadinessStateDefinitionTransformerInterface::class);
        $definitionTransformer->expects(self::once())->method('transform')
            ->with($definitionData)
            ->willReturn($definition);

        $api = $this->buildApi($headers, $requestSender, $definitionTransformer, self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        self::assertSame($definition, $api->getOneById(77));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $definition = self::createStub(ShopReadinessStateDefinitionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['definition-self']);

        $definitionTransformer = self::createStub(ShopReadinessStateDefinitionTransformerInterface::class);
        $definitionTransformer->method('transform')->willReturn($definition);

        $api = $this->buildApi([], $requestSender, $definitionTransformer, self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        self::assertSame($definition, $api->getOneById(77, true));
        self::assertSame($definition, $api->getOneById(77, true));
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReadinessStateDefinitionApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(77, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReadinessStateDefinitionApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(77);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $definition = self::createStub(ShopReadinessStateDefinitionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['definition-self']);

        $definitionTransformer = self::createStub(ShopReadinessStateDefinitionTransformerInterface::class);
        $definitionTransformer->method('transform')->willReturn($definition);

        $api = $this->buildApi([], $requestSender, $definitionTransformer, self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        self::assertSame($definition, $api->getOneById(77));
        self::assertSame($definition, $api->getOneById(77));
    }

    public function testUpdateReturnsDefinition(): void
    {
        $headers = ['x-api-key' => 'key'];
        $definitionData = ['definition-self'];
        $definition = self::createStub(ShopReadinessStateDefinitionInterface::class);
        $serializedBody = ['min_processing_time' => '1'];

        $updateRequest = self::createStub(UpdateShopReadinessStateDefinitionRequestInterface::class);
        $updateRequestSerializer = self::createMock(UpdateShopReadinessStateDefinitionRequestSerializerInterface::class);
        $updateRequestSerializer->expects(self::once())->method('serialize')
            ->with($updateRequest)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('putForm')
            ->with(
                sprintf(ShopReadinessStateDefinitionApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 77),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($definitionData);

        $definitionTransformer = self::createMock(ShopReadinessStateDefinitionTransformerInterface::class);
        $definitionTransformer->expects(self::once())->method('transform')
            ->with($definitionData)
            ->willReturn($definition);

        $api = $this->buildApi($headers, $requestSender, $definitionTransformer, self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class), null, null, $updateRequestSerializer);

        self::assertSame($definition, $api->update(77, $updateRequest));
    }

    public function testUpdateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('putForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopReadinessStateDefinitionTransformerInterface::class), self::createStub(ShopReadinessStateDefinitionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReadinessStateDefinitionApiInterface::UNEXPECTED_RESPONSE);

        $api->update(77, self::createStub(UpdateShopReadinessStateDefinitionRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ShopReadinessStateDefinitionTransformerInterface $shopReadinessStateDefinitionTransformer, ShopReadinessStateDefinitionsTransformerInterface $shopReadinessStateDefinitionsTransformer, ?ApiRequestSenderInterface $apiRequestSender = null, ?CreateShopReadinessStateDefinitionRequestSerializerInterface $createShopReadinessStateDefinitionRequestSerializer = null, ?UpdateShopReadinessStateDefinitionRequestSerializerInterface $updateShopReadinessStateDefinitionRequestSerializer = null): ShopReadinessStateDefinitionApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopReadinessStateDefinitionApi(
            $requestSender,
            $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class),
            $shopReadinessStateDefinitionTransformer,
            $shopReadinessStateDefinitionsTransformer,
            $createShopReadinessStateDefinitionRequestSerializer ?? self::createStub(CreateShopReadinessStateDefinitionRequestSerializerInterface::class),
            $updateShopReadinessStateDefinitionRequestSerializer ?? self::createStub(UpdateShopReadinessStateDefinitionRequestSerializerInterface::class),
            $credentials,
            self::SHOP_ID,
        );
    }
}
