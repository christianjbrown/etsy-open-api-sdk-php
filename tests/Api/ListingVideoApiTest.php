<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ListingVideoApi;
use ChristianBrown\Etsy\Api\ListingVideoApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingVideoInterface;
use ChristianBrown\Etsy\Transformer\ListingVideosTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingVideoTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingVideoApi::class)]
final class ListingVideoApiTest extends TestCase
{
    private const int LISTING_ID = 7;

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

        self::assertSame($videos, $api->getMultiple(self::LISTING_ID, true));
        self::assertSame($videos, $api->getMultiple(self::LISTING_ID, true));
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

        self::assertSame($videos, $api->getMultiple(self::LISTING_ID));
        self::assertSame($videos, $api->getMultiple(self::LISTING_ID));
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

        self::assertSame($video, $api->getOneById(self::LISTING_ID, 99, true));
        self::assertSame($video, $api->getOneById(self::LISTING_ID, 99, true));
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

        self::assertSame($video, $api->getOneById(self::LISTING_ID, 99));
        self::assertSame($video, $api->getOneById(self::LISTING_ID, 99));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingVideoTransformerInterface $videoTransformer, ListingVideosTransformerInterface $videosTransformer): ListingVideoApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingVideoApi($requestSender, $videoTransformer, $videosTransformer, $credentials);
    }
}
