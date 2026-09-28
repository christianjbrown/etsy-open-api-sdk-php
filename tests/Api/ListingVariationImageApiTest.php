<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ListingVariationImageApi;
use ChristianBrown\Etsy\Api\ListingVariationImageApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCache;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingVariationImageInterface;
use ChristianBrown\Etsy\Model\UpdateVariationImagesRequestInterface;
use ChristianBrown\Etsy\Serializer\UpdateVariationImagesRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingVariationImagesTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingVariationImageApi::class)]
#[UsesClass(ResponseCache::class)]
final class ListingVariationImageApiTest extends TestCase
{
    private const int LISTING_ID = 7;
    private const int SHOP_ID = 42;

    public function testGetMultipleReturnsVariationImages(): void
    {
        $resultsData = [['variation-1'], ['variation-2']];
        $headers = ['x-api-key' => 'key'];
        $variations = [self::createStub(ListingVariationImageInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingVariationImageApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID, self::LISTING_ID),
                [],
                $headers,
            )
            ->willReturn([ListingVariationImageApiInterface::KEY_RESULTS => $resultsData]);

        $variationsTransformer = self::createMock(ListingVariationImagesTransformerInterface::class);
        $variationsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($variations);

        $api = $this->buildApi($headers, $requestSender, $variationsTransformer);

        self::assertSame($variations, $api->getMultiple(self::LISTING_ID));
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $variations = [self::createStub(ListingVariationImageInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ListingVariationImageApiInterface::KEY_RESULTS => [['variation-1']]]);

        $variationsTransformer = self::createStub(ListingVariationImagesTransformerInterface::class);
        $variationsTransformer->method('transform')->willReturn($variations);

        $api = $this->buildApi([], $requestSender, $variationsTransformer);

        $first = $api->getMultiple(self::LISTING_ID, true);
        $second = $api->getMultiple(self::LISTING_ID, true);

        self::assertSame($variations, $first);
        self::assertSame($variations, $second);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingVariationImageApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVariationImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVariationImageApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingVariationImageApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID, true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingVariationImageApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVariationImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVariationImageApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingVariationImageApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID, true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingVariationImageApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVariationImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVariationImageApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingVariationImageApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID);
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingVariationImageApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVariationImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVariationImageApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingVariationImageApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID);
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $variations = [self::createStub(ListingVariationImageInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ListingVariationImageApiInterface::KEY_RESULTS => [['variation-1']]]);

        $variationsTransformer = self::createStub(ListingVariationImagesTransformerInterface::class);
        $variationsTransformer->method('transform')->willReturn($variations);

        $api = $this->buildApi([], $requestSender, $variationsTransformer);

        $first = $api->getMultiple(self::LISTING_ID);
        $second = $api->getMultiple(self::LISTING_ID);

        self::assertSame($variations, $first);
        self::assertSame($variations, $second);
    }

    public function testUpdateReturnsVariationImages(): void
    {
        $resultsData = [['variation-1']];
        $headers = ['x-api-key' => 'key'];
        $variations = [self::createStub(ListingVariationImageInterface::class)];
        $serializedBody = ['variation_images' => []];

        $request = self::createStub(UpdateVariationImagesRequestInterface::class);
        $requestSerializer = self::createMock(UpdateVariationImagesRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('post')
            ->with(
                sprintf(ListingVariationImageApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID, self::LISTING_ID),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn([ListingVariationImageApiInterface::KEY_RESULTS => $resultsData]);

        $variationsTransformer = self::createMock(ListingVariationImagesTransformerInterface::class);
        $variationsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($variations);

        $api = $this->buildApi($headers, $requestSender, $variationsTransformer, $requestSerializer);

        self::assertSame($variations, $api->update(self::LISTING_ID, $request));
    }

    public function testUpdateThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('post')->willReturn([ListingVariationImageApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVariationImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVariationImageApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingVariationImageApiInterface::KEY_RESULTS));

        $api->update(self::LISTING_ID, self::createStub(UpdateVariationImagesRequestInterface::class));
    }

    public function testUpdateThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('post')->willReturn([ListingVariationImageApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVariationImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVariationImageApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingVariationImageApiInterface::KEY_RESULTS));

        $api->update(self::LISTING_ID, self::createStub(UpdateVariationImagesRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingVariationImagesTransformerInterface $variationsTransformer, ?UpdateVariationImagesRequestSerializerInterface $updateVariationImagesRequestSerializer = null): ListingVariationImageApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingVariationImageApi($requestSender, $variationsTransformer, $updateVariationImagesRequestSerializer ?? self::createStub(UpdateVariationImagesRequestSerializerInterface::class), new ResponseCache(), $credentials, self::SHOP_ID);
    }
}
