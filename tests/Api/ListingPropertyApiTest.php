<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ListingPropertyApi;
use ChristianBrown\Etsy\Api\ListingPropertyApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingPropertyValueInterface;
use ChristianBrown\Etsy\Model\UpdateListingPropertyRequestInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingPropertyRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingPropertyValueTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingPropertyApi::class)]
final class ListingPropertyApiTest extends TestCase
{
    private const int LISTING_ID = 7;
    private const int SHOP_ID = 42;

    public function testGetMultipleReturnsPropertyValues(): void
    {
        $resultsData = [['property-1'], ['property-2']];
        $headers = ['x-api-key' => 'key'];
        $properties = [self::createStub(ListingPropertyValueInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingPropertyApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID, self::LISTING_ID),
                [],
                $headers,
            )
            ->willReturn([ListingPropertyApiInterface::KEY_RESULTS => $resultsData]);

        $valuesTransformer = self::createMock(ListingPropertyValuesTransformerInterface::class);
        $valuesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($properties);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ListingPropertyValueTransformerInterface::class), $valuesTransformer);

        self::assertSame($properties, $api->getMultiple(self::LISTING_ID));
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $properties = [self::createStub(ListingPropertyValueInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ListingPropertyApiInterface::KEY_RESULTS => [['property-1']]]);

        $valuesTransformer = self::createStub(ListingPropertyValuesTransformerInterface::class);
        $valuesTransformer->method('transform')->willReturn($properties);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPropertyValueTransformerInterface::class), $valuesTransformer);

        self::assertSame($properties, $api->getMultiple(self::LISTING_ID, true));
        self::assertSame($properties, $api->getMultiple(self::LISTING_ID, true));
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingPropertyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPropertyValueTransformerInterface::class), self::createStub(ListingPropertyValuesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingPropertyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingPropertyApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID, true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingPropertyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPropertyValueTransformerInterface::class), self::createStub(ListingPropertyValuesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingPropertyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingPropertyApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID, true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingPropertyApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPropertyValueTransformerInterface::class), self::createStub(ListingPropertyValuesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingPropertyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingPropertyApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID);
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingPropertyApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPropertyValueTransformerInterface::class), self::createStub(ListingPropertyValuesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingPropertyApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingPropertyApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID);
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $properties = [self::createStub(ListingPropertyValueInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ListingPropertyApiInterface::KEY_RESULTS => [['property-1']]]);

        $valuesTransformer = self::createStub(ListingPropertyValuesTransformerInterface::class);
        $valuesTransformer->method('transform')->willReturn($properties);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPropertyValueTransformerInterface::class), $valuesTransformer);

        self::assertSame($properties, $api->getMultiple(self::LISTING_ID));
        self::assertSame($properties, $api->getMultiple(self::LISTING_ID));
    }

    public function testGetOneByIdReturnsPropertyValue(): void
    {
        $propertyData = ['property-99'];
        $headers = ['x-api-key' => 'key'];
        $property = self::createStub(ListingPropertyValueInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingPropertyApiInterface::API_URL_ONE_SPRINTF, self::LISTING_ID, 99),
                [],
                $headers,
            )
            ->willReturn($propertyData);

        $valueTransformer = self::createMock(ListingPropertyValueTransformerInterface::class);
        $valueTransformer->expects(self::once())->method('transform')
            ->with($propertyData)
            ->willReturn($property);

        $api = $this->buildApi($headers, $requestSender, $valueTransformer, self::createStub(ListingPropertyValuesTransformerInterface::class));

        self::assertSame($property, $api->getOneById(self::LISTING_ID, 99));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $property = self::createStub(ListingPropertyValueInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['property-99']);

        $valueTransformer = self::createStub(ListingPropertyValueTransformerInterface::class);
        $valueTransformer->method('transform')->willReturn($property);

        $api = $this->buildApi([], $requestSender, $valueTransformer, self::createStub(ListingPropertyValuesTransformerInterface::class));

        self::assertSame($property, $api->getOneById(self::LISTING_ID, 99, true));
        self::assertSame($property, $api->getOneById(self::LISTING_ID, 99, true));
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPropertyValueTransformerInterface::class), self::createStub(ListingPropertyValuesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingPropertyApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(self::LISTING_ID, 99, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPropertyValueTransformerInterface::class), self::createStub(ListingPropertyValuesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingPropertyApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(self::LISTING_ID, 99);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $property = self::createStub(ListingPropertyValueInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['property-99']);

        $valueTransformer = self::createStub(ListingPropertyValueTransformerInterface::class);
        $valueTransformer->method('transform')->willReturn($property);

        $api = $this->buildApi([], $requestSender, $valueTransformer, self::createStub(ListingPropertyValuesTransformerInterface::class));

        self::assertSame($property, $api->getOneById(self::LISTING_ID, 99));
        self::assertSame($property, $api->getOneById(self::LISTING_ID, 99));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingPropertyValueTransformerInterface $valueTransformer, ListingPropertyValuesTransformerInterface $valuesTransformer, ?ApiRequestSenderInterface $apiRequestSender = null, ?UpdateListingPropertyRequestSerializerInterface $updateListingPropertyRequestSerializer = null): ListingPropertyApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingPropertyApi($requestSender, $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class), $valueTransformer, $valuesTransformer, $updateListingPropertyRequestSerializer ?? self::createStub(UpdateListingPropertyRequestSerializerInterface::class), $credentials, self::SHOP_ID);
    }

    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ListingPropertyApiInterface::API_URL_WRITE_SPRINTF, self::SHOP_ID, 10, 20),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ListingPropertyValueTransformerInterface::class), self::createStub(ListingPropertyValuesTransformerInterface::class), $apiRequestSender);

        $api->delete(10, 20);
    }

    public function testUpdateReturnsPropertyValue(): void
    {
        $headers = ['x-api-key' => 'key'];
        $valueData = ['value-self'];
        $value = self::createStub(ListingPropertyValueInterface::class);
        $serializedBody = ['value_ids[0]' => '1'];

        $request = self::createStub(UpdateListingPropertyRequestInterface::class);
        $requestSerializer = self::createMock(UpdateListingPropertyRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('putForm')
            ->with(
                sprintf(ListingPropertyApiInterface::API_URL_WRITE_SPRINTF, self::SHOP_ID, 10, 20),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($valueData);

        $valueTransformer = self::createMock(ListingPropertyValueTransformerInterface::class);
        $valueTransformer->expects(self::once())->method('transform')
            ->with($valueData)
            ->willReturn($value);

        $api = $this->buildApi($headers, $requestSender, $valueTransformer, self::createStub(ListingPropertyValuesTransformerInterface::class), null, $requestSerializer);

        self::assertSame($value, $api->update(10, 20, $request));
    }

    public function testUpdateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('putForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPropertyValueTransformerInterface::class), self::createStub(ListingPropertyValuesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingPropertyApiInterface::UNEXPECTED_RESPONSE);

        $api->update(10, 20, self::createStub(UpdateListingPropertyRequestInterface::class));
    }
}
