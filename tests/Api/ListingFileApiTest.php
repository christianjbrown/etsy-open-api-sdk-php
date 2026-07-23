<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ListingFileApi;
use ChristianBrown\Etsy\Api\ListingFileApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingFileInterface;
use ChristianBrown\Etsy\Transformer\ListingFilesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingFileTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingFileApi::class)]
final class ListingFileApiTest extends TestCase
{
    private const int LISTING_ID = 7;
    private const int SHOP_ID = 42;

    public function testGetMultipleReturnsFiles(): void
    {
        $resultsData = [['file-1'], ['file-2']];
        $headers = ['x-api-key' => 'key'];
        $files = [self::createStub(ListingFileInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingFileApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID, self::LISTING_ID),
                [],
                $headers,
            )
            ->willReturn([ListingFileApiInterface::KEY_RESULTS => $resultsData]);

        $filesTransformer = self::createMock(ListingFilesTransformerInterface::class);
        $filesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($files);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ListingFileTransformerInterface::class), $filesTransformer);

        self::assertSame($files, $api->getMultiple(self::LISTING_ID));
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $files = [self::createStub(ListingFileInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ListingFileApiInterface::KEY_RESULTS => [['file-1']]]);

        $filesTransformer = self::createStub(ListingFilesTransformerInterface::class);
        $filesTransformer->method('transform')->willReturn($files);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingFileTransformerInterface::class), $filesTransformer);

        self::assertSame($files, $api->getMultiple(self::LISTING_ID, true));
        self::assertSame($files, $api->getMultiple(self::LISTING_ID, true));
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingFileApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingFileTransformerInterface::class), self::createStub(ListingFilesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingFileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingFileApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID, true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingFileApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingFileTransformerInterface::class), self::createStub(ListingFilesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingFileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingFileApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID, true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingFileApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingFileTransformerInterface::class), self::createStub(ListingFilesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingFileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingFileApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID);
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingFileApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingFileTransformerInterface::class), self::createStub(ListingFilesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingFileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingFileApiInterface::KEY_RESULTS));

        $api->getMultiple(self::LISTING_ID);
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $files = [self::createStub(ListingFileInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ListingFileApiInterface::KEY_RESULTS => [['file-1']]]);

        $filesTransformer = self::createStub(ListingFilesTransformerInterface::class);
        $filesTransformer->method('transform')->willReturn($files);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingFileTransformerInterface::class), $filesTransformer);

        self::assertSame($files, $api->getMultiple(self::LISTING_ID));
        self::assertSame($files, $api->getMultiple(self::LISTING_ID));
    }

    public function testGetOneByIdReturnsFile(): void
    {
        $fileData = ['file-99'];
        $headers = ['x-api-key' => 'key'];
        $file = self::createStub(ListingFileInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingFileApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, self::LISTING_ID, 99),
                [],
                $headers,
            )
            ->willReturn($fileData);

        $fileTransformer = self::createMock(ListingFileTransformerInterface::class);
        $fileTransformer->expects(self::once())->method('transform')
            ->with($fileData)
            ->willReturn($file);

        $api = $this->buildApi($headers, $requestSender, $fileTransformer, self::createStub(ListingFilesTransformerInterface::class));

        self::assertSame($file, $api->getOneById(self::LISTING_ID, 99));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $file = self::createStub(ListingFileInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['file-99']);

        $fileTransformer = self::createStub(ListingFileTransformerInterface::class);
        $fileTransformer->method('transform')->willReturn($file);

        $api = $this->buildApi([], $requestSender, $fileTransformer, self::createStub(ListingFilesTransformerInterface::class));

        self::assertSame($file, $api->getOneById(self::LISTING_ID, 99, true));
        self::assertSame($file, $api->getOneById(self::LISTING_ID, 99, true));
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingFileTransformerInterface::class), self::createStub(ListingFilesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingFileApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(self::LISTING_ID, 99, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingFileTransformerInterface::class), self::createStub(ListingFilesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingFileApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(self::LISTING_ID, 99);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $file = self::createStub(ListingFileInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['file-99']);

        $fileTransformer = self::createStub(ListingFileTransformerInterface::class);
        $fileTransformer->method('transform')->willReturn($file);

        $api = $this->buildApi([], $requestSender, $fileTransformer, self::createStub(ListingFilesTransformerInterface::class));

        self::assertSame($file, $api->getOneById(self::LISTING_ID, 99));
        self::assertSame($file, $api->getOneById(self::LISTING_ID, 99));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingFileTransformerInterface $fileTransformer, ListingFilesTransformerInterface $filesTransformer): ListingFileApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingFileApi($requestSender, $fileTransformer, $filesTransformer, $credentials, self::SHOP_ID);
    }
}
