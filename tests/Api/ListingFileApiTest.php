<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformerInterface;
use ChristianBrown\Etsy\Api\ListingFileApi;
use ChristianBrown\Etsy\Api\ListingFileApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Http\MultipartFormDataBuilderInterface;
use ChristianBrown\Etsy\Model\ListingFileInterface;
use ChristianBrown\Etsy\Model\UploadListingFileRequestInterface;
use ChristianBrown\Etsy\Serializer\UploadListingFileRequestSerializerInterface;
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

    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ListingFileApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, self::LISTING_ID, 99),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ListingFileTransformerInterface::class), self::createStub(ListingFilesTransformerInterface::class), $apiRequestSender);

        $api->delete(self::LISTING_ID, 99);
    }

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

        $first = $api->getMultiple(self::LISTING_ID, true);
        $second = $api->getMultiple(self::LISTING_ID, true);

        self::assertSame($files, $first);
        self::assertSame($files, $second);
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

        $first = $api->getMultiple(self::LISTING_ID);
        $second = $api->getMultiple(self::LISTING_ID);

        self::assertSame($files, $first);
        self::assertSame($files, $second);
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

        $first = $api->getOneById(self::LISTING_ID, 99, true);
        $second = $api->getOneById(self::LISTING_ID, 99, true);

        self::assertSame($file, $first);
        self::assertSame($file, $second);
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

        $first = $api->getOneById(self::LISTING_ID, 99);
        $second = $api->getOneById(self::LISTING_ID, 99);

        self::assertSame($file, $first);
        self::assertSame($file, $second);
    }

    public function testUploadReturnsFile(): void
    {
        $headers = ['x-api-key' => 'key'];
        $fileData = ['file-99'];
        $file = self::createStub(ListingFileInterface::class);
        $file->method('getListingFileId')->willReturn(99);
        $serializedFields = ['name' => 'invoice.pdf'];

        $request = self::createStub(UploadListingFileRequestInterface::class);
        $requestSerializer = self::createMock(UploadListingFileRequestSerializerInterface::class);
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
                sprintf(ListingFileApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID, self::LISTING_ID),
                [],
                $headers + ['Content-Type' => 'multipart/form-data; boundary=boundary-1'],
                'multipart-body',
            )
            ->willReturn('{"listing_file_id":99}');

        $jsonToArrayTransformer = self::createStub(JsonToArrayTransformerInterface::class);
        $jsonToArrayTransformer->method('transform')->willReturn($fileData);

        $fileTransformer = self::createMock(ListingFileTransformerInterface::class);
        $fileTransformer->expects(self::once())->method('transform')
            ->with($fileData)
            ->willReturn($file);

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), $fileTransformer, self::createStub(ListingFilesTransformerInterface::class), $apiRequestSender, $multipartFormDataBuilder, $jsonToArrayTransformer, $requestSerializer);

        self::assertSame($file, $api->upload(self::LISTING_ID, $request));
    }

    public function testUploadThrowsWhenEmpty(): void
    {
        $apiRequestSender = self::createStub(ApiRequestSenderInterface::class);
        $apiRequestSender->method('post')->willReturn('[]');

        $jsonToArrayTransformer = self::createStub(JsonToArrayTransformerInterface::class);
        $jsonToArrayTransformer->method('transform')->willReturn([]);

        $api = $this->buildApi([], self::createStub(JsonApiRequestSenderInterface::class), self::createStub(ListingFileTransformerInterface::class), self::createStub(ListingFilesTransformerInterface::class), $apiRequestSender, null, $jsonToArrayTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingFileApiInterface::UNEXPECTED_RESPONSE);

        $api->upload(self::LISTING_ID, self::createStub(UploadListingFileRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingFileTransformerInterface $fileTransformer, ListingFilesTransformerInterface $filesTransformer, ?ApiRequestSenderInterface $apiRequestSender = null, ?MultipartFormDataBuilderInterface $multipartFormDataBuilder = null, ?JsonToArrayTransformerInterface $jsonToArrayTransformer = null, ?UploadListingFileRequestSerializerInterface $uploadListingFileRequestSerializer = null): ListingFileApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingFileApi(
            $requestSender,
            $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class),
            $fileTransformer,
            $filesTransformer,
            $multipartFormDataBuilder ?? self::createStub(MultipartFormDataBuilderInterface::class),
            $jsonToArrayTransformer ?? self::createStub(JsonToArrayTransformerInterface::class),
            $uploadListingFileRequestSerializer ?? self::createStub(UploadListingFileRequestSerializerInterface::class),
            $credentials,
            self::SHOP_ID,
        );
    }
}
