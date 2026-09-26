<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformerInterface;
use ChristianBrown\Etsy\Api\ListingImageApi;
use ChristianBrown\Etsy\Api\ListingImageApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Http\MultipartFormDataBuilderInterface;
use ChristianBrown\Etsy\Model\ListingImageInterface;
use ChristianBrown\Etsy\Model\UploadListingImageRequestInterface;
use ChristianBrown\Etsy\Serializer\UploadListingImageRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingImagesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingImageTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingImageApi::class)]
final class ListingImageApiTest extends TestCase
{
    private const int LISTING_ID = 7;
    private const int SHOP_ID = 42;

    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ListingImageApiInterface::API_URL_WRITE_ONE_SPRINTF, self::SHOP_ID, self::LISTING_ID, 99),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ListingImageTransformerInterface::class), self::createStub(ListingImagesTransformerInterface::class), $apiRequestSender);

        $api->delete(self::LISTING_ID, 99);
    }

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

        $first = $api->getMultiple(self::LISTING_ID, true);
        $second = $api->getMultiple(self::LISTING_ID, true);

        self::assertSame($images, $first);
        self::assertSame($images, $second);
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

        $first = $api->getMultiple(self::LISTING_ID);
        $second = $api->getMultiple(self::LISTING_ID);

        self::assertSame($images, $first);
        self::assertSame($images, $second);
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

        $first = $api->getOneById(self::LISTING_ID, 99, true);
        $second = $api->getOneById(self::LISTING_ID, 99, true);

        self::assertSame($image, $first);
        self::assertSame($image, $second);
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

        $first = $api->getOneById(self::LISTING_ID, 99);
        $second = $api->getOneById(self::LISTING_ID, 99);

        self::assertSame($image, $first);
        self::assertSame($image, $second);
    }

    public function testUploadReturnsImage(): void
    {
        $headers = ['x-api-key' => 'key'];
        $imageData = ['image-99'];
        $image = self::createStub(ListingImageInterface::class);
        $image->method('getListingImageId')->willReturn(99);
        $serializedFields = ['rank' => '1'];

        $request = self::createStub(UploadListingImageRequestInterface::class);
        $requestSerializer = self::createMock(UploadListingImageRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedFields);

        $multipartFormDataBuilder = self::createMock(MultipartFormDataBuilderInterface::class);
        $multipartFormDataBuilder->expects(self::once())->method('generateBoundary')->willReturn('boundary-1');
        $multipartFormDataBuilder->expects(self::once())->method('build')
            ->with('boundary-1', $serializedFields, null)
            ->willReturn('multipart-body');
        $multipartFormDataBuilder->expects(self::once())->method('toContentTypeHeaderValue')
            ->with('boundary-1')
            ->willReturn('multipart/form-data; boundary=boundary-1');

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('post')
            ->with(
                sprintf(ListingImageApiInterface::API_URL_WRITE_MULTIPLE_SPRINTF, self::SHOP_ID, self::LISTING_ID),
                [],
                $headers + ['Content-Type' => 'multipart/form-data; boundary=boundary-1'],
                'multipart-body',
            )
            ->willReturn('{"listing_image_id":99}');

        $jsonToArrayTransformer = self::createStub(JsonToArrayTransformerInterface::class);
        $jsonToArrayTransformer->method('transform')->willReturn($imageData);

        $imageTransformer = self::createMock(ListingImageTransformerInterface::class);
        $imageTransformer->expects(self::once())->method('transform')
            ->with($imageData)
            ->willReturn($image);

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), $imageTransformer, self::createStub(ListingImagesTransformerInterface::class), $apiRequestSender, $multipartFormDataBuilder, $jsonToArrayTransformer, $requestSerializer);

        self::assertSame($image, $api->upload(self::LISTING_ID, $request));
    }

    public function testUploadThrowsWhenEmpty(): void
    {
        $apiRequestSender = self::createStub(ApiRequestSenderInterface::class);
        $apiRequestSender->method('post')->willReturn('[]');

        $jsonToArrayTransformer = self::createStub(JsonToArrayTransformerInterface::class);
        $jsonToArrayTransformer->method('transform')->willReturn([]);

        $api = $this->buildApi([], self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ListingImageTransformerInterface::class), self::createStub(ListingImagesTransformerInterface::class), $apiRequestSender, null, $jsonToArrayTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingImageApiInterface::UNEXPECTED_RESPONSE);

        $api->upload(self::LISTING_ID, self::createStub(UploadListingImageRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingImageTransformerInterface $imageTransformer, ListingImagesTransformerInterface $imagesTransformer, ?ApiRequestSenderInterface $apiRequestSender = null, ?MultipartFormDataBuilderInterface $multipartFormDataBuilder = null, ?JsonToArrayTransformerInterface $jsonToArrayTransformer = null, ?UploadListingImageRequestSerializerInterface $uploadListingImageRequestSerializer = null): ListingImageApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingImageApi(
            $requestSender,
            $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class),
            $imageTransformer,
            $imagesTransformer,
            $multipartFormDataBuilder ?? self::createStub(MultipartFormDataBuilderInterface::class),
            $jsonToArrayTransformer ?? self::createStub(JsonToArrayTransformerInterface::class),
            $uploadListingImageRequestSerializer ?? self::createStub(UploadListingImageRequestSerializerInterface::class),
            $credentials,
            self::SHOP_ID,
        );
    }
}
