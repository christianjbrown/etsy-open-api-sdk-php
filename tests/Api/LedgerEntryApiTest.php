<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\LedgerEntryApi;
use ChristianBrown\Etsy\Api\LedgerEntryApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCache;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntryInterface;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntriesTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntryTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(LedgerEntryApi::class)]
#[UsesClass(ResponseCache::class)]
final class LedgerEntryApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testGetMultipleReturnsEntriesWithMinAndMaxCreated(): void
    {
        $resultsData = [['entry-1'], ['entry-2']];
        $headers = ['x-api-key' => 'key'];
        $entries = [self::createStub(PaymentAccountLedgerEntryInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(LedgerEntryApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [
                    LedgerEntryApiInterface::KEY_LIMIT => '10',
                    LedgerEntryApiInterface::KEY_OFFSET => '5',
                    LedgerEntryApiInterface::KEY_MIN_CREATED => '100',
                    LedgerEntryApiInterface::KEY_MAX_CREATED => '200',
                ],
                $headers,
            )
            ->willReturn([LedgerEntryApiInterface::KEY_RESULTS => $resultsData]);

        $entriesTransformer = self::createMock(PaymentAccountLedgerEntriesTransformerInterface::class);
        $entriesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($entries);

        $api = $this->buildApi($headers, $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), $entriesTransformer);

        self::assertSame($entries, $api->getMultiple(100, 200, 10, 5));
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $entries = [self::createStub(PaymentAccountLedgerEntryInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([LedgerEntryApiInterface::KEY_RESULTS => [['entry-1']]]);

        $entriesTransformer = self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class);
        $entriesTransformer->method('transform')->willReturn($entries);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), $entriesTransformer);

        $first = $api->getMultiple(null, null, 25, 0, true);
        $second = $api->getMultiple(null, null, 25, 0, true);

        self::assertSame($entries, $first);
        self::assertSame($entries, $second);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([LedgerEntryApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(LedgerEntryApiInterface::UNEXPECTED_RESPONSE_SPRINTF, LedgerEntryApiInterface::KEY_RESULTS));

        $api->getMultiple(null, null, 25, 0, true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([LedgerEntryApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(LedgerEntryApiInterface::UNEXPECTED_RESPONSE_SPRINTF, LedgerEntryApiInterface::KEY_RESULTS));

        $api->getMultiple(null, null, 25, 0, true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([LedgerEntryApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(LedgerEntryApiInterface::UNEXPECTED_RESPONSE_SPRINTF, LedgerEntryApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([LedgerEntryApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(LedgerEntryApiInterface::UNEXPECTED_RESPONSE_SPRINTF, LedgerEntryApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $entries = [self::createStub(PaymentAccountLedgerEntryInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([LedgerEntryApiInterface::KEY_RESULTS => [['entry-1']]]);

        $entriesTransformer = self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class);
        $entriesTransformer->method('transform')->willReturn($entries);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), $entriesTransformer);

        $first = $api->getMultiple();
        $second = $api->getMultiple();

        self::assertSame($entries, $first);
        self::assertSame($entries, $second);
    }

    public function testGetMultipleWithMaxCreatedOnly(): void
    {
        $entries = [self::createStub(PaymentAccountLedgerEntryInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(LedgerEntryApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [
                    LedgerEntryApiInterface::KEY_LIMIT => '25',
                    LedgerEntryApiInterface::KEY_OFFSET => '0',
                    LedgerEntryApiInterface::KEY_MAX_CREATED => '200',
                ],
                [],
            )
            ->willReturn([LedgerEntryApiInterface::KEY_RESULTS => [['entry-1']]]);

        $entriesTransformer = self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class);
        $entriesTransformer->method('transform')->willReturn($entries);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), $entriesTransformer);

        self::assertSame($entries, $api->getMultiple(null, 200));
    }

    public function testGetMultipleWithMinCreatedOnly(): void
    {
        $entries = [self::createStub(PaymentAccountLedgerEntryInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(LedgerEntryApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [
                    LedgerEntryApiInterface::KEY_LIMIT => '25',
                    LedgerEntryApiInterface::KEY_OFFSET => '0',
                    LedgerEntryApiInterface::KEY_MIN_CREATED => '100',
                ],
                [],
            )
            ->willReturn([LedgerEntryApiInterface::KEY_RESULTS => [['entry-1']]]);

        $entriesTransformer = self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class);
        $entriesTransformer->method('transform')->willReturn($entries);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), $entriesTransformer);

        self::assertSame($entries, $api->getMultiple(100));
    }

    public function testGetOneByIdReturnsEntry(): void
    {
        $entryData = ['entry-99'];
        $headers = ['x-api-key' => 'key'];
        $entry = self::createStub(PaymentAccountLedgerEntryInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(LedgerEntryApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 99),
                [],
                $headers,
            )
            ->willReturn($entryData);

        $entryTransformer = self::createMock(PaymentAccountLedgerEntryTransformerInterface::class);
        $entryTransformer->expects(self::once())->method('transform')
            ->with($entryData)
            ->willReturn($entry);

        $api = $this->buildApi($headers, $requestSender, $entryTransformer, self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class));

        self::assertSame($entry, $api->getOneById(99));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $entry = self::createStub(PaymentAccountLedgerEntryInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['entry-99']);

        $entryTransformer = self::createStub(PaymentAccountLedgerEntryTransformerInterface::class);
        $entryTransformer->method('transform')->willReturn($entry);

        $api = $this->buildApi([], $requestSender, $entryTransformer, self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class));

        $first = $api->getOneById(99, true);
        $second = $api->getOneById(99, true);

        self::assertSame($entry, $first);
        self::assertSame($entry, $second);
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(LedgerEntryApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(99, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentAccountLedgerEntryTransformerInterface::class), self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(LedgerEntryApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(99);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $entry = self::createStub(PaymentAccountLedgerEntryInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['entry-99']);

        $entryTransformer = self::createStub(PaymentAccountLedgerEntryTransformerInterface::class);
        $entryTransformer->method('transform')->willReturn($entry);

        $api = $this->buildApi([], $requestSender, $entryTransformer, self::createStub(PaymentAccountLedgerEntriesTransformerInterface::class));

        $first = $api->getOneById(99);
        $second = $api->getOneById(99);

        self::assertSame($entry, $first);
        self::assertSame($entry, $second);
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, PaymentAccountLedgerEntryTransformerInterface $entryTransformer, PaymentAccountLedgerEntriesTransformerInterface $entriesTransformer): LedgerEntryApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new LedgerEntryApi($requestSender, $entryTransformer, $entriesTransformer, new ResponseCache(), new ResponseCache(), $credentials, self::SHOP_ID);
    }
}
