<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopReceiptTransactionApi;
use ChristianBrown\Etsy\Api\ShopReceiptTransactionApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TransactionInterface;
use ChristianBrown\Etsy\Transformer\TransactionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\TransactionTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopReceiptTransactionApi::class)]
final class ShopReceiptTransactionApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testGetByListingReturnsTransactions(): void
    {
        $resultsData = [['transaction-1'], ['transaction-2']];
        $headers = ['x-api-key' => 'key'];
        $transactions = [self::createStub(TransactionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopReceiptTransactionApiInterface::API_URL_BY_LISTING_SPRINTF, self::SHOP_ID, 77),
                [
                    ShopReceiptTransactionApiInterface::KEY_LIMIT => '10',
                    ShopReceiptTransactionApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => $resultsData]);

        $transactionsTransformer = self::createMock(TransactionsTransformerInterface::class);
        $transactionsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($transactions);

        $api = $this->buildApi($headers, $requestSender, self::createStub(TransactionTransformerInterface::class), $transactionsTransformer);

        self::assertSame($transactions, $api->getByListing(77, 10, 5));
    }

    public function testGetByListingSkipCacheRefetches(): void
    {
        $transactions = [self::createStub(TransactionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => [['transaction-1']]]);

        $transactionsTransformer = self::createStub(TransactionsTransformerInterface::class);
        $transactionsTransformer->method('transform')->willReturn($transactions);

        $api = $this->buildApi([], $requestSender, self::createStub(TransactionTransformerInterface::class), $transactionsTransformer);

        self::assertSame($transactions, $api->getByListing(77, 25, 0, true));
        self::assertSame($transactions, $api->getByListing(77, 25, 0, true));
    }

    public function testGetByListingUsesCacheOnSecondCall(): void
    {
        $transactions = [self::createStub(TransactionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => [['transaction-1']]]);

        $transactionsTransformer = self::createStub(TransactionsTransformerInterface::class);
        $transactionsTransformer->method('transform')->willReturn($transactions);

        $api = $this->buildApi([], $requestSender, self::createStub(TransactionTransformerInterface::class), $transactionsTransformer);

        self::assertSame($transactions, $api->getByListing(77));
        self::assertSame($transactions, $api->getByListing(77));
    }

    public function testGetByReceiptReturnsTransactions(): void
    {
        $resultsData = [['transaction-1']];
        $headers = ['x-api-key' => 'key'];
        $transactions = [self::createStub(TransactionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopReceiptTransactionApiInterface::API_URL_BY_RECEIPT_SPRINTF, self::SHOP_ID, 88),
                [],
                $headers,
            )
            ->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => $resultsData]);

        $transactionsTransformer = self::createMock(TransactionsTransformerInterface::class);
        $transactionsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($transactions);

        $api = $this->buildApi($headers, $requestSender, self::createStub(TransactionTransformerInterface::class), $transactionsTransformer);

        self::assertSame($transactions, $api->getByReceipt(88));
    }

    public function testGetByReceiptSkipCacheRefetches(): void
    {
        $transactions = [self::createStub(TransactionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => [['transaction-1']]]);

        $transactionsTransformer = self::createStub(TransactionsTransformerInterface::class);
        $transactionsTransformer->method('transform')->willReturn($transactions);

        $api = $this->buildApi([], $requestSender, self::createStub(TransactionTransformerInterface::class), $transactionsTransformer);

        self::assertSame($transactions, $api->getByReceipt(88, true));
        self::assertSame($transactions, $api->getByReceipt(88, true));
    }

    public function testGetByReceiptSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(TransactionTransformerInterface::class), self::createStub(TransactionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReceiptTransactionApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReceiptTransactionApiInterface::KEY_RESULTS));

        $api->getByReceipt(88, true);
    }

    public function testGetByReceiptThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(TransactionTransformerInterface::class), self::createStub(TransactionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReceiptTransactionApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReceiptTransactionApiInterface::KEY_RESULTS));

        $api->getByReceipt(88);
    }

    public function testGetByReceiptUsesCacheOnSecondCall(): void
    {
        $transactions = [self::createStub(TransactionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => [['transaction-1']]]);

        $transactionsTransformer = self::createStub(TransactionsTransformerInterface::class);
        $transactionsTransformer->method('transform')->willReturn($transactions);

        $api = $this->buildApi([], $requestSender, self::createStub(TransactionTransformerInterface::class), $transactionsTransformer);

        self::assertSame($transactions, $api->getByReceipt(88));
        self::assertSame($transactions, $api->getByReceipt(88));
    }

    public function testGetByShopReturnsTransactions(): void
    {
        $resultsData = [['transaction-1']];
        $headers = ['x-api-key' => 'key'];
        $transactions = [self::createStub(TransactionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopReceiptTransactionApiInterface::API_URL_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ShopReceiptTransactionApiInterface::KEY_LIMIT => '10',
                    ShopReceiptTransactionApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => $resultsData]);

        $transactionsTransformer = self::createMock(TransactionsTransformerInterface::class);
        $transactionsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($transactions);

        $api = $this->buildApi($headers, $requestSender, self::createStub(TransactionTransformerInterface::class), $transactionsTransformer);

        self::assertSame($transactions, $api->getByShop(10, 5));
    }

    public function testGetByShopSkipCacheRefetches(): void
    {
        $transactions = [self::createStub(TransactionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => [['transaction-1']]]);

        $transactionsTransformer = self::createStub(TransactionsTransformerInterface::class);
        $transactionsTransformer->method('transform')->willReturn($transactions);

        $api = $this->buildApi([], $requestSender, self::createStub(TransactionTransformerInterface::class), $transactionsTransformer);

        self::assertSame($transactions, $api->getByShop(25, 0, true));
        self::assertSame($transactions, $api->getByShop(25, 0, true));
    }

    public function testGetByShopUsesCacheOnSecondCall(): void
    {
        $transactions = [self::createStub(TransactionInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopReceiptTransactionApiInterface::KEY_RESULTS => [['transaction-1']]]);

        $transactionsTransformer = self::createStub(TransactionsTransformerInterface::class);
        $transactionsTransformer->method('transform')->willReturn($transactions);

        $api = $this->buildApi([], $requestSender, self::createStub(TransactionTransformerInterface::class), $transactionsTransformer);

        self::assertSame($transactions, $api->getByShop());
        self::assertSame($transactions, $api->getByShop());
    }

    public function testGetOneByIdReturnsTransaction(): void
    {
        $transactionData = ['transaction-99'];
        $headers = ['x-api-key' => 'key'];
        $transaction = self::createStub(TransactionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopReceiptTransactionApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 99),
                [],
                $headers,
            )
            ->willReturn($transactionData);

        $transactionTransformer = self::createMock(TransactionTransformerInterface::class);
        $transactionTransformer->expects(self::once())->method('transform')
            ->with($transactionData)
            ->willReturn($transaction);

        $api = $this->buildApi($headers, $requestSender, $transactionTransformer, self::createStub(TransactionsTransformerInterface::class));

        self::assertSame($transaction, $api->getOneById(99));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $transaction = self::createStub(TransactionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['transaction-99']);

        $transactionTransformer = self::createStub(TransactionTransformerInterface::class);
        $transactionTransformer->method('transform')->willReturn($transaction);

        $api = $this->buildApi([], $requestSender, $transactionTransformer, self::createStub(TransactionsTransformerInterface::class));

        self::assertSame($transaction, $api->getOneById(99, true));
        self::assertSame($transaction, $api->getOneById(99, true));
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(TransactionTransformerInterface::class), self::createStub(TransactionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReceiptTransactionApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(99, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(TransactionTransformerInterface::class), self::createStub(TransactionsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReceiptTransactionApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(99);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $transaction = self::createStub(TransactionInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['transaction-99']);

        $transactionTransformer = self::createStub(TransactionTransformerInterface::class);
        $transactionTransformer->method('transform')->willReturn($transaction);

        $api = $this->buildApi([], $requestSender, $transactionTransformer, self::createStub(TransactionsTransformerInterface::class));

        self::assertSame($transaction, $api->getOneById(99));
        self::assertSame($transaction, $api->getOneById(99));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, TransactionTransformerInterface $transactionTransformer, TransactionsTransformerInterface $transactionsTransformer): ShopReceiptTransactionApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopReceiptTransactionApi($requestSender, $transactionTransformer, $transactionsTransformer, $credentials, self::SHOP_ID);
    }
}
