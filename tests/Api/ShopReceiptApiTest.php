<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopReceiptApi;
use ChristianBrown\Etsy\Api\ShopReceiptApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\CreateReceiptShipmentRequestInterface;
use ChristianBrown\Etsy\Model\ReceiptInterface;
use ChristianBrown\Etsy\Model\ReceiptPageInterface;
use ChristianBrown\Etsy\Model\UpdateShopReceiptRequestInterface;
use ChristianBrown\Etsy\Serializer\CreateReceiptShipmentRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopReceiptRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptPageTransformerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopReceiptApi::class)]
final class ShopReceiptApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testCreateReceiptShipmentReturnsReceipt(): void
    {
        $headers = ['x-api-key' => 'key'];
        $receiptData = ['receipt-self'];
        $receipt = self::createStub(ReceiptInterface::class);
        $serializedBody = ['tracking_code' => 'abc'];

        $request = self::createStub(CreateReceiptShipmentRequestInterface::class);
        $requestSerializer = self::createMock(CreateReceiptShipmentRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('post')
            ->with(
                sprintf(ShopReceiptApiInterface::API_URL_TRACKING_SPRINTF, self::SHOP_ID, 77),
                [ShopReceiptApiInterface::KEY_LEGACY => 'true'],
                $headers,
                $serializedBody,
            )
            ->willReturn($receiptData);

        $receiptTransformer = self::createMock(ReceiptTransformerInterface::class);
        $receiptTransformer->expects(self::once())->method('transform')
            ->with($receiptData)
            ->willReturn($receipt);

        $api = $this->buildApi($headers, $requestSender, $receiptTransformer, self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class), $requestSerializer);

        self::assertSame($receipt, $api->createReceiptShipment(77, $request, true));
    }

    public function testCreateReceiptShipmentThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('post')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReceiptApiInterface::UNEXPECTED_RESPONSE);

        $api->createReceiptShipment(77, self::createStub(CreateReceiptShipmentRequestInterface::class));
    }

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

        $api = $this->buildApi($headers, $requestSender, self::createStub(ReceiptTransformerInterface::class), $receiptsTransformer, self::createStub(ReceiptPageTransformerInterface::class));

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

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), $receiptsTransformer, self::createStub(ReceiptPageTransformerInterface::class));

        $first = $api->getMultiple(100, 0, true);
        $second = $api->getMultiple(100, 0, true);

        self::assertSame($receipts, $first);
        self::assertSame($receipts, $second);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReceiptApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReceiptApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReceiptApiInterface::KEY_RESULTS));

        $api->getMultiple(100, 0, true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReceiptApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReceiptApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReceiptApiInterface::KEY_RESULTS));

        $api->getMultiple(100, 0, true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReceiptApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReceiptApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopReceiptApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopReceiptApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

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

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), $receiptsTransformer, self::createStub(ReceiptPageTransformerInterface::class));

        $first = $api->getMultiple();
        $second = $api->getMultiple();

        self::assertSame($receipts, $first);
        self::assertSame($receipts, $second);
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

        $api = $this->buildApi($headers, $requestSender, $receiptTransformer, self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

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

        $api = $this->buildApi([], $requestSender, $receiptTransformer, self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

        $first = $api->getOneById(99, true);
        $second = $api->getOneById(99, true);

        self::assertSame($receipt, $first);
        self::assertSame($receipt, $second);
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReceiptApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(99, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

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

        $api = $this->buildApi([], $requestSender, $receiptTransformer, self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

        $first = $api->getOneById(99);
        $second = $api->getOneById(99);

        self::assertSame($receipt, $first);
        self::assertSame($receipt, $second);
    }

    public function testGetPageReturnsPage(): void
    {
        $responseData = [
            ShopReceiptApiInterface::KEY_RESULTS => [['receipt-1']],
        ];
        $headers = ['x-api-key' => 'key'];
        $page = self::createStub(ReceiptPageInterface::class);

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
            ->willReturn($responseData);

        $receiptPageTransformer = self::createMock(ReceiptPageTransformerInterface::class);
        $receiptPageTransformer->expects(self::once())->method('transform')
            ->with($responseData)
            ->willReturn($page);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), $receiptPageTransformer);

        self::assertSame($page, $api->getPage(10, 5));
    }

    public function testGetPageSkipCacheRefetches(): void
    {
        $page = self::createStub(ReceiptPageInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopReceiptApiInterface::KEY_RESULTS => [['receipt-1']]]);

        $receiptPageTransformer = self::createStub(ReceiptPageTransformerInterface::class);
        $receiptPageTransformer->method('transform')->willReturn($page);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), $receiptPageTransformer);

        $first = $api->getPage(100, 0, true);
        $second = $api->getPage(100, 0, true);

        self::assertSame($page, $first);
        self::assertSame($page, $second);
    }

    public function testGetPageSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReceiptApiInterface::UNEXPECTED_RESPONSE);

        $api->getPage(100, 0, true);
    }

    public function testGetPageThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReceiptApiInterface::UNEXPECTED_RESPONSE);

        $api->getPage();
    }

    public function testGetPageUsesCacheOnSecondCall(): void
    {
        $page = self::createStub(ReceiptPageInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopReceiptApiInterface::KEY_RESULTS => [['receipt-1']]]);

        $receiptPageTransformer = self::createStub(ReceiptPageTransformerInterface::class);
        $receiptPageTransformer->method('transform')->willReturn($page);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), $receiptPageTransformer);

        $first = $api->getPage();
        $second = $api->getPage();

        self::assertSame($page, $first);
        self::assertSame($page, $second);
    }

    public function testUpdateShopReceiptReturnsReceipt(): void
    {
        $headers = ['x-api-key' => 'key'];
        $receiptData = ['receipt-self'];
        $receipt = self::createStub(ReceiptInterface::class);
        $serializedBody = ['was_shipped' => 'true'];

        $request = self::createStub(UpdateShopReceiptRequestInterface::class);
        $requestSerializer = self::createMock(UpdateShopReceiptRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('putForm')
            ->with(
                sprintf(ShopReceiptApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, 77),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($receiptData);

        $receiptTransformer = self::createMock(ReceiptTransformerInterface::class);
        $receiptTransformer->expects(self::once())->method('transform')
            ->with($receiptData)
            ->willReturn($receipt);

        $api = $this->buildApi($headers, $requestSender, $receiptTransformer, self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class), null, $requestSerializer);

        self::assertSame($receipt, $api->updateShopReceipt(77, $request));
    }

    public function testUpdateShopReceiptThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('putForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReceiptTransformerInterface::class), self::createStub(ReceiptsTransformerInterface::class), self::createStub(ReceiptPageTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopReceiptApiInterface::UNEXPECTED_RESPONSE);

        $api->updateShopReceipt(77, self::createStub(UpdateShopReceiptRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ReceiptTransformerInterface $receiptTransformer, ReceiptsTransformerInterface $receiptsTransformer, ReceiptPageTransformerInterface $receiptPageTransformer, ?CreateReceiptShipmentRequestSerializerInterface $createReceiptShipmentRequestSerializer = null, ?UpdateShopReceiptRequestSerializerInterface $updateShopReceiptRequestSerializer = null): ShopReceiptApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopReceiptApi(
            $requestSender,
            $receiptTransformer,
            $receiptsTransformer,
            $receiptPageTransformer,
            $createReceiptShipmentRequestSerializer ?? self::createStub(CreateReceiptShipmentRequestSerializerInterface::class),
            $updateShopReceiptRequestSerializer ?? self::createStub(UpdateShopReceiptRequestSerializerInterface::class),
            $credentials,
            self::SHOP_ID,
        );
    }
}
