<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\PaymentApi;
use ChristianBrown\Etsy\Api\PaymentApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentInterface;
use ChristianBrown\Etsy\Transformer\PaymentsTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(PaymentApi::class)]
final class PaymentApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testGetByLedgerEntryIdsReturnsPayments(): void
    {
        $resultsData = [['payment-1']];
        $headers = ['x-api-key' => 'key'];
        $payments = [self::createStub(PaymentInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(PaymentApiInterface::API_URL_BY_LEDGER_ENTRY_IDS_SPRINTF, self::SHOP_ID),
                [PaymentApiInterface::KEY_LEDGER_ENTRY_IDS => '7,8'],
                $headers,
            )
            ->willReturn([PaymentApiInterface::KEY_RESULTS => $resultsData]);

        $paymentsTransformer = self::createMock(PaymentsTransformerInterface::class);
        $paymentsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($payments);

        $api = $this->buildApi($headers, $requestSender, $paymentsTransformer);

        self::assertSame($payments, $api->getByLedgerEntryIds([7, 8]));
    }

    public function testGetByLedgerEntryIdsSkipCacheRefetches(): void
    {
        $payments = [self::createStub(PaymentInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([PaymentApiInterface::KEY_RESULTS => [['payment-1']]]);

        $paymentsTransformer = self::createStub(PaymentsTransformerInterface::class);
        $paymentsTransformer->method('transform')->willReturn($payments);

        $api = $this->buildApi([], $requestSender, $paymentsTransformer);

        $first = $api->getByLedgerEntryIds([7, 8], true);
        $second = $api->getByLedgerEntryIds([7, 8], true);

        self::assertSame($payments, $first);
        self::assertSame($payments, $second);
    }

    public function testGetByLedgerEntryIdsUsesCacheOnSecondCall(): void
    {
        $payments = [self::createStub(PaymentInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([PaymentApiInterface::KEY_RESULTS => [['payment-1']]]);

        $paymentsTransformer = self::createStub(PaymentsTransformerInterface::class);
        $paymentsTransformer->method('transform')->willReturn($payments);

        $api = $this->buildApi([], $requestSender, $paymentsTransformer);

        $first = $api->getByLedgerEntryIds([7, 8]);
        $second = $api->getByLedgerEntryIds([7, 8]);

        self::assertSame($payments, $first);
        self::assertSame($payments, $second);
    }

    public function testGetByPaymentIdsReturnsPayments(): void
    {
        $resultsData = [['payment-1'], ['payment-2']];
        $headers = ['x-api-key' => 'key'];
        $payments = [self::createStub(PaymentInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(PaymentApiInterface::API_URL_BY_PAYMENT_IDS_SPRINTF, self::SHOP_ID),
                [PaymentApiInterface::KEY_PAYMENT_IDS => '1,2'],
                $headers,
            )
            ->willReturn([PaymentApiInterface::KEY_RESULTS => $resultsData]);

        $paymentsTransformer = self::createMock(PaymentsTransformerInterface::class);
        $paymentsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($payments);

        $api = $this->buildApi($headers, $requestSender, $paymentsTransformer);

        self::assertSame($payments, $api->getByPaymentIds([1, 2]));
    }

    public function testGetByPaymentIdsSkipCacheRefetches(): void
    {
        $payments = [self::createStub(PaymentInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([PaymentApiInterface::KEY_RESULTS => [['payment-1']]]);

        $paymentsTransformer = self::createStub(PaymentsTransformerInterface::class);
        $paymentsTransformer->method('transform')->willReturn($payments);

        $api = $this->buildApi([], $requestSender, $paymentsTransformer);

        $first = $api->getByPaymentIds([1, 2], true);
        $second = $api->getByPaymentIds([1, 2], true);

        self::assertSame($payments, $first);
        self::assertSame($payments, $second);
    }

    public function testGetByPaymentIdsSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([PaymentApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PaymentApiInterface::UNEXPECTED_RESPONSE_SPRINTF, PaymentApiInterface::KEY_RESULTS));

        $api->getByPaymentIds([1, 2], true);
    }

    public function testGetByPaymentIdsThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([PaymentApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(PaymentsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PaymentApiInterface::UNEXPECTED_RESPONSE_SPRINTF, PaymentApiInterface::KEY_RESULTS));

        $api->getByPaymentIds([1, 2]);
    }

    public function testGetByPaymentIdsUsesCacheOnSecondCall(): void
    {
        $payments = [self::createStub(PaymentInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([PaymentApiInterface::KEY_RESULTS => [['payment-1']]]);

        $paymentsTransformer = self::createStub(PaymentsTransformerInterface::class);
        $paymentsTransformer->method('transform')->willReturn($payments);

        $api = $this->buildApi([], $requestSender, $paymentsTransformer);

        $first = $api->getByPaymentIds([1, 2]);
        $second = $api->getByPaymentIds([1, 2]);

        self::assertSame($payments, $first);
        self::assertSame($payments, $second);
    }

    public function testGetByReceiptReturnsPayments(): void
    {
        $resultsData = [['payment-1']];
        $headers = ['x-api-key' => 'key'];
        $payments = [self::createStub(PaymentInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(PaymentApiInterface::API_URL_BY_RECEIPT_SPRINTF, self::SHOP_ID, 88),
                [],
                $headers,
            )
            ->willReturn([PaymentApiInterface::KEY_RESULTS => $resultsData]);

        $paymentsTransformer = self::createMock(PaymentsTransformerInterface::class);
        $paymentsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($payments);

        $api = $this->buildApi($headers, $requestSender, $paymentsTransformer);

        self::assertSame($payments, $api->getByReceipt(88));
    }

    public function testGetByReceiptSkipCacheRefetches(): void
    {
        $payments = [self::createStub(PaymentInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([PaymentApiInterface::KEY_RESULTS => [['payment-1']]]);

        $paymentsTransformer = self::createStub(PaymentsTransformerInterface::class);
        $paymentsTransformer->method('transform')->willReturn($payments);

        $api = $this->buildApi([], $requestSender, $paymentsTransformer);

        $first = $api->getByReceipt(88, true);
        $second = $api->getByReceipt(88, true);

        self::assertSame($payments, $first);
        self::assertSame($payments, $second);
    }

    public function testGetByReceiptUsesCacheOnSecondCall(): void
    {
        $payments = [self::createStub(PaymentInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([PaymentApiInterface::KEY_RESULTS => [['payment-1']]]);

        $paymentsTransformer = self::createStub(PaymentsTransformerInterface::class);
        $paymentsTransformer->method('transform')->willReturn($payments);

        $api = $this->buildApi([], $requestSender, $paymentsTransformer);

        $first = $api->getByReceipt(88);
        $second = $api->getByReceipt(88);

        self::assertSame($payments, $first);
        self::assertSame($payments, $second);
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, PaymentsTransformerInterface $paymentsTransformer): PaymentApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new PaymentApi($requestSender, $paymentsTransformer, $credentials, self::SHOP_ID);
    }
}
