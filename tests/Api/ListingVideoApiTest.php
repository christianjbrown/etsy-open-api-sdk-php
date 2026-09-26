<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformerInterface;
use ChristianBrown\Etsy\Api\ListingVideoApi;
use ChristianBrown\Etsy\Api\ListingVideoApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Http\MultipartFormDataBuilderInterface;
use ChristianBrown\Etsy\Model\ListingVideoInterface;
use ChristianBrown\Etsy\Model\UploadListingVideoRequestInterface;
use ChristianBrown\Etsy\Serializer\UploadListingVideoRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingVideosTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingVideoTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingVideoApi::class)]
final class ListingVideoApiTest extends TestCase
{
    private const int LISTING_ID = 7;
    private const int SHOP_ID = 42;

    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ListingVideoApiInterface::API_URL_WRITE_ONE_SPRINTF, self::SHOP_ID, self::LISTING_ID, 99),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ListingVideoTransformerInterface::class), self::createStub(ListingVideosTransformerInterface::class), $apiRequestSender);

        $api->delete(self::LISTING_ID, 99);
    }

    public function testGetMultipleReturnsVideos(): void
    {
        $resultsData = [['video-1'], ['video-2']];
        $headers = ['x-api-key' => 'key'];
        $videos = [self::createStub(ListingVideoInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingVideoApiInterface::API_URL_MULTIPLE_SPRINTF, self::LISTING_ID),
                [],
                $headers,
            )
            ->willReturn([ListingVideoApiInterface::KEY_RESULTS => $resultsData]);

        $videosTransformer = self::createMock(ListingVideosTransformerInterface::class);
        $videosTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($videos);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ListingVideoTransformerInterface::class), $videosTransformer);

        self::assertSame($videos, $api->getMultiple(self::LISTING_ID));
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $videos = [self::createStub(ListingVideoInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ListingVideoApiInterface::KEY_RESULTS => [['video-1']]]);

        $videosTransformer = self::createStub(ListingVideosTransformerInterface::class);
        $videosTransformer->method('transform')->willReturn($videos);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVideoTransformerInterface::class), $videosTransformer);

        $first = $api->getMultiple(self::LISTING_ID, true);
        $second = $api->getMultiple(self::LISTING_ID, true);

        self::assertSame($videos, $first);
        self::assertSame($videos, $second);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingVideoApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVideoTransformerInterface::class), self::createStub(ListingVideosTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVideoApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingVideoApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID, true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingVideoApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVideoTransformerInterface::class), self::createStub(ListingVideosTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVideoApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingVideoApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID, true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingVideoApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVideoTransformerInterface::class), self::createStub(ListingVideosTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVideoApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingVideoApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID);
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingVideoApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVideoTransformerInterface::class), self::createStub(ListingVideosTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVideoApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingVideoApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID);
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $videos = [self::createStub(ListingVideoInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ListingVideoApiInterface::KEY_RESULTS => [['video-1']]]);

        $videosTransformer = self::createStub(ListingVideosTransformerInterface::class);
        $videosTransformer->method('transform')->willReturn($videos);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVideoTransformerInterface::class), $videosTransformer);

        $first = $api->getMultiple(self::LISTING_ID);
        $second = $api->getMultiple(self::LISTING_ID);

        self::assertSame($videos, $first);
        self::assertSame($videos, $second);
    }

    public function testGetOneByIdReturnsVideo(): void
    {
        $videoData = ['video-99'];
        $headers = ['x-api-key' => 'key'];
        $video = self::createStub(ListingVideoInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingVideoApiInterface::API_URL_ONE_SPRINTF, self::LISTING_ID, 99),
                [],
                $headers,
            )
            ->willReturn($videoData);

        $videoTransformer = self::createMock(ListingVideoTransformerInterface::class);
        $videoTransformer->expects(self::once())->method('transform')
            ->with($videoData)
            ->willReturn($video);

        $api = $this->buildApi($headers, $requestSender, $videoTransformer, self::createStub(ListingVideosTransformerInterface::class));

        self::assertSame($video, $api->getOneById(self::LISTING_ID, 99));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $video = self::createStub(ListingVideoInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['video-99']);

        $videoTransformer = self::createStub(ListingVideoTransformerInterface::class);
        $videoTransformer->method('transform')->willReturn($video);

        $api = $this->buildApi([], $requestSender, $videoTransformer, self::createStub(ListingVideosTransformerInterface::class));

        $first = $api->getOneById(self::LISTING_ID, 99, true);
        $second = $api->getOneById(self::LISTING_ID, 99, true);

        self::assertSame($video, $first);
        self::assertSame($video, $second);
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVideoTransformerInterface::class), self::createStub(ListingVideosTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingVideoApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(self::LISTING_ID, 99, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingVideoTransformerInterface::class), self::createStub(ListingVideosTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingVideoApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(self::LISTING_ID, 99);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $video = self::createStub(ListingVideoInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['video-99']);

        $videoTransformer = self::createStub(ListingVideoTransformerInterface::class);
        $videoTransformer->method('transform')->willReturn($video);

        $api = $this->buildApi([], $requestSender, $videoTransformer, self::createStub(ListingVideosTransformerInterface::class));

        $first = $api->getOneById(self::LISTING_ID, 99);
        $second = $api->getOneById(self::LISTING_ID, 99);

        self::assertSame($video, $first);
        self::assertSame($video, $second);
    }

    public function testUploadReturnsVideo(): void
    {
        $headers = ['x-api-key' => 'key'];
        $videoData = ['video-99'];
        $video = self::createStub(ListingVideoInterface::class);
        $video->method('getVideoId')->willReturn(99);
        $serializedFields = ['name' => 'demo.mp4'];

        $request = self::createStub(UploadListingVideoRequestInterface::class);
        $requestSerializer = self::createMock(UploadListingVideoRequestSerializerInterface::class);
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
                sprintf(ListingVideoApiInterface::API_URL_WRITE_MULTIPLE_SPRINTF, self::SHOP_ID, self::LISTING_ID),
                [],
                $headers + ['Content-Type' => 'multipart/form-data; boundary=boundary-1'],
                'multipart-body',
            )
            ->willReturn('{"video_id":99}');

        $jsonToArrayTransformer = self::createStub(JsonToArrayTransformerInterface::class);
        $jsonToArrayTransformer->method('transform')->willReturn($videoData);

        $videoTransformer = self::createMock(ListingVideoTransformerInterface::class);
        $videoTransformer->expects(self::once())->method('transform')
            ->with($videoData)
            ->willReturn($video);

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), $videoTransformer, self::createStub(ListingVideosTransformerInterface::class), $apiRequestSender, $multipartFormDataBuilder, $jsonToArrayTransformer, $requestSerializer);

        self::assertSame($video, $api->upload(self::LISTING_ID, $request));
    }

    public function testUploadThrowsWhenEmpty(): void
    {
        $apiRequestSender = self::createStub(ApiRequestSenderInterface::class);
        $apiRequestSender->method('post')->willReturn('[]');

        $jsonToArrayTransformer = self::createStub(JsonToArrayTransformerInterface::class);
        $jsonToArrayTransformer->method('transform')->willReturn([]);

        $api = $this->buildApi([], self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ListingVideoTransformerInterface::class), self::createStub(ListingVideosTransformerInterface::class), $apiRequestSender, null, $jsonToArrayTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingVideoApiInterface::UNEXPECTED_RESPONSE);

        $api->upload(self::LISTING_ID, self::createStub(UploadListingVideoRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingVideoTransformerInterface $videoTransformer, ListingVideosTransformerInterface $videosTransformer, ?ApiRequestSenderInterface $apiRequestSender = null, ?MultipartFormDataBuilderInterface $multipartFormDataBuilder = null, ?JsonToArrayTransformerInterface $jsonToArrayTransformer = null, ?UploadListingVideoRequestSerializerInterface $uploadListingVideoRequestSerializer = null): ListingVideoApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingVideoApi(
            $requestSender,
            $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class),
            $videoTransformer,
            $videosTransformer,
            $multipartFormDataBuilder ?? self::createStub(MultipartFormDataBuilderInterface::class),
            $jsonToArrayTransformer ?? self::createStub(JsonToArrayTransformerInterface::class),
            $uploadListingVideoRequestSerializer ?? self::createStub(UploadListingVideoRequestSerializerInterface::class),
            $credentials,
            self::SHOP_ID,
        );
    }
}
