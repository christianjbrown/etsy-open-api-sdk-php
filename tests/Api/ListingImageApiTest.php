<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ListingImageApi;
use ChristianBrown\Etsy\Api\ListingImageApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingImageInterface;
use ChristianBrown\Etsy\Transformer\ListingImagesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingImageTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingImageApi::class)]
final class ListingImageApiTest extends TestCase
{
    private const int LISTING_ID = 7;

    public function testGetMultipleReturnsImages(): void
    {
        $resultsData = [['image-1'], ['image-2']];
        $headers = ['x-api-key' => 'key'];
        $images = [self::createStub(ListingImageInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingImageApiInterface::API_URL_MULTIPLE_SPRINTF, self::LISTING_ID),
                [],
                $headers,
            )
            ->willReturn([ListingImageApiInterface::KEY_RESULTS => $resultsData]);

        $imagesTransformer = self::createMock(ListingImagesTransformerInterface::class);
        $imagesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($images);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ListingImageTransformerInterface::class), $imagesTransformer);

        self::assertSame($images, $api->getMultiple(self::LISTING_ID));
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $images = [self::createStub(ListingImageInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ListingImageApiInterface::KEY_RESULTS => [['image-1']]]);

        $imagesTransformer = self::createStub(ListingImagesTransformerInterface::class);
        $imagesTransformer->method('transform')->willReturn($images);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingImageTransformerInterface::class), $imagesTransformer);

        self::assertSame($images, $api->getMultiple(self::LISTING_ID, true));
        self::assertSame($images, $api->getMultiple(self::LISTING_ID, true));
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingImageApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingImageTransformerInterface::class), self::createStub(ListingImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingImageApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingImageApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID, true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingImageApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingImageTransformerInterface::class), self::createStub(ListingImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingImageApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingImageApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID, true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingImageApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingImageTransformerInterface::class), self::createStub(ListingImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingImageApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingImageApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID);
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingImageApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingImageTransformerInterface::class), self::createStub(ListingImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingImageApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingImageApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID);
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $images = [self::createStub(ListingImageInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ListingImageApiInterface::KEY_RESULTS => [['image-1']]]);

        $imagesTransformer = self::createStub(ListingImagesTransformerInterface::class);
        $imagesTransformer->method('transform')->willReturn($images);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingImageTransformerInterface::class), $imagesTransformer);

        self::assertSame($images, $api->getMultiple(self::LISTING_ID));
        self::assertSame($images, $api->getMultiple(self::LISTING_ID));
    }

    public function testGetOneByIdReturnsImage(): void
    {
        $imageData = ['image-99'];
        $headers = ['x-api-key' => 'key'];
        $image = self::createStub(ListingImageInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingImageApiInterface::API_URL_ONE_SPRINTF, self::LISTING_ID, 99),
                [],
                $headers,
            )
            ->willReturn($imageData);

        $imageTransformer = self::createMock(ListingImageTransformerInterface::class);
        $imageTransformer->expects(self::once())->method('transform')
            ->with($imageData)
            ->willReturn($image);

        $api = $this->buildApi($headers, $requestSender, $imageTransformer, self::createStub(ListingImagesTransformerInterface::class));

        self::assertSame($image, $api->getOneById(self::LISTING_ID, 99));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $image = self::createStub(ListingImageInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['image-99']);

        $imageTransformer = self::createStub(ListingImageTransformerInterface::class);
        $imageTransformer->method('transform')->willReturn($image);

        $api = $this->buildApi([], $requestSender, $imageTransformer, self::createStub(ListingImagesTransformerInterface::class));

        self::assertSame($image, $api->getOneById(self::LISTING_ID, 99, true));
        self::assertSame($image, $api->getOneById(self::LISTING_ID, 99, true));
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingImageTransformerInterface::class), self::createStub(ListingImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingImageApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(self::LISTING_ID, 99, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingImageTransformerInterface::class), self::createStub(ListingImagesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingImageApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(self::LISTING_ID, 99);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $image = self::createStub(ListingImageInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['image-99']);

        $imageTransformer = self::createStub(ListingImageTransformerInterface::class);
        $imageTransformer->method('transform')->willReturn($image);

        $api = $this->buildApi([], $requestSender, $imageTransformer, self::createStub(ListingImagesTransformerInterface::class));

        self::assertSame($image, $api->getOneById(self::LISTING_ID, 99));
        self::assertSame($image, $api->getOneById(self::LISTING_ID, 99));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingImageTransformerInterface $imageTransformer, ListingImagesTransformerInterface $imagesTransformer): ListingImageApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingImageApi($requestSender, $imageTransformer, $imagesTransformer, $credentials);
    }
}
