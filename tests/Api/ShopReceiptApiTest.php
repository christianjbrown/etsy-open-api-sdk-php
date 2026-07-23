<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopReceiptApi;
use ChristianBrown\Etsy\Api\ShopReceiptApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ReceiptInterface;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopReceiptApi::class)]
final class ShopReceiptApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testGetMultipleReturnsReceipts(): void
    {
        $resultsData = [['receipt-1'], ['receipt-2']];
        $headers = ['x-api-key' => 'key'];
        $receipts = [self::createStub(ReceiptInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopReceiptApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [
                    ShopReceiptApiInterface::KEY_LIMIT => '10',
                    ShopReceiptApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ShopReceiptApiInterface::KEY_RESULTS => $resultsData]);

        $receiptsTransformer = self::createMock(ReceiptsTransformerInterface::class);
        $receiptsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($receipts);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ReceiptTransformerInterface::class), $receiptsTransformer);

        self::assertSame($receipts, $api->getMultiple(10, 5));
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $receipts = [self::createStub(ReceiptInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopReceiptApiInterface::KEY_RESULTS => [['receipt-1']]]);

        $receiptsTransformer = self::createStub(ReceiptsTransformerInterface::class);
        $receiptsTransformer->method('transform')->willReturn($receipts);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), $receiptsTransformer);

        self::assertSame($receipts, $api->getMultiple(100, 0, true));
        self::assertSame($receipts, $api->getMultiple(100, 0, true));
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReceiptApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReceiptApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReceiptApiInterface::KEY_RESULTS));

        $api->getMultiple(100, 0, true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReceiptApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReceiptApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReceiptApiInterface::KEY_RESULTS));

        $api->getMultiple(100, 0, true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReceiptApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReceiptApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReceiptApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReceiptApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReceiptApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReceiptApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $receipts = [self::createStub(ReceiptInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopReceiptApiInterface::KEY_RESULTS => [['receipt-1']]]);

        $receiptsTransformer = self::createStub(ReceiptsTransformerInterface::class);
        $receiptsTransformer->method('transform')->willReturn($receipts);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), $receiptsTransformer);

        self::assertSame($receipts, $api->getMultiple());
        self::assertSame($receipts, $api->getMultiple());
    }

    public function testGetOneByIdReturnsReceipt(): void
    {
        $receiptData = ['receipt-99'];
        $headers = ['x-api-key' => 'key'];
        $receipt = self::createStub(ReceiptInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopReceiptApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 99),
                [],
                $headers,
            )
            ->willReturn($receiptData);

        $receiptTransformer = self::createMock(ReceiptTransformerInterface::class);
        $receiptTransformer->expects(self::once())->method('transform')
            ->with($receiptData)
            ->willReturn($receipt);

        $api = $this->buildApi($headers, $requestSender, $receiptTransformer, self::createStub(ReceiptsTransformerInterface::class));

        self::assertSame($receipt, $api->getOneById(99));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $receipt = self::createStub(ReceiptInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['receipt-99']);

        $receiptTransformer = self::createStub(ReceiptTransformerInterface::class);
        $receiptTransformer->method('transform')->willReturn($receipt);

        $api = $this->buildApi([], $requestSender, $receiptTransformer, self::createStub(ReceiptsTransformerInterface::class));

        self::assertSame($receipt, $api->getOneById(99, true));
        self::assertSame($receipt, $api->getOneById(99, true));
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReceiptApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(99, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReceiptApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(99);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $receipt = self::createStub(ReceiptInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['receipt-99']);

        $receiptTransformer = self::createStub(ReceiptTransformerInterface::class);
        $receiptTransformer->method('transform')->willReturn($receipt);

        $api = $this->buildApi([], $requestSender, $receiptTransformer, self::createStub(ReceiptsTransformerInterface::class));

        self::assertSame($receipt, $api->getOneById(99));
        self::assertSame($receipt, $api->getOneById(99));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ReceiptTransformerInterface $receiptTransformer, ReceiptsTransformerInterface $receiptsTransformer): ShopReceiptApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopReceiptApi($requestSender, $receiptTransformer, $receiptsTransformer, $credentials, self::SHOP_ID);
    }
}
