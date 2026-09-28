<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ListingInventoryApi;
use ChristianBrown\Etsy\Api\ListingInventoryApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCache;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;
use ChristianBrown\Etsy\Model\UpdateListingInventoryRequestInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingInventoryRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingInventoryApi::class)]
#[UsesClass(ResponseCache::class)]
final class ListingInventoryApiTest extends TestCase
{
    private const int LISTING_ID = 7;
    private const int OFFERING_ID = 99;
    private const int PRODUCT_ID = 55;

    public function testGetByListingIdReturnsInventory(): void
    {
        $inventoryData = ['inventory-data'];
        $headers = ['x-api-key' => 'key'];
        $inventory = self::createStub(ListingInventoryInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingInventoryApiInterface::API_URL_BY_LISTING_ID_SPRINTF, self::LISTING_ID),
                [],
                $headers,
            )
            ->willReturn($inventoryData);

        $inventoryTransformer = self::createMock(ListingInventoryTransformerInterface::class);
        $inventoryTransformer->expects(self::once())->method('transform')
            ->with($inventoryData)
            ->willReturn($inventory);

        $api = $this->buildApi($headers, $requestSender, $inventoryTransformer, self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        self::assertSame($inventory, $api->getByListingId(self::LISTING_ID));
    }

    public function testGetByListingIdSkipCacheRefetches(): void
    {
        $inventory = self::createStub(ListingInventoryInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['inventory-data']);

        $inventoryTransformer = self::createStub(ListingInventoryTransformerInterface::class);
        $inventoryTransformer->method('transform')->willReturn($inventory);

        $api = $this->buildApi([], $requestSender, $inventoryTransformer, self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $first = $api->getByListingId(self::LISTING_ID, true);
        $second = $api->getByListingId(self::LISTING_ID, true);

        self::assertSame($inventory, $first);
        self::assertSame($inventory, $second);
    }

    public function testGetByListingIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingInventoryApiInterface::UNEXPECTED_RESPONSE);

        $api->getByListingId(self::LISTING_ID, true);
    }

    public function testGetByListingIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingInventoryApiInterface::UNEXPECTED_RESPONSE);

        $api->getByListingId(self::LISTING_ID);
    }

    public function testGetByListingIdUsesCacheOnSecondCall(): void
    {
        $inventory = self::createStub(ListingInventoryInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['inventory-data']);

        $inventoryTransformer = self::createStub(ListingInventoryTransformerInterface::class);
        $inventoryTransformer->method('transform')->willReturn($inventory);

        $api = $this->buildApi([], $requestSender, $inventoryTransformer, self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $first = $api->getByListingId(self::LISTING_ID);
        $second = $api->getByListingId(self::LISTING_ID);

        self::assertSame($inventory, $first);
        self::assertSame($inventory, $second);
    }

    public function testGetOfferingReturnsOffering(): void
    {
        $offeringData = ['offering-data'];
        $headers = ['x-api-key' => 'key'];
        $offering = self::createStub(ListingInventoryProductOfferingInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingInventoryApiInterface::API_URL_OFFERING_SPRINTF, self::LISTING_ID, self::PRODUCT_ID, self::OFFERING_ID),
                [],
                $headers,
            )
            ->willReturn($offeringData);

        $offeringTransformer = self::createMock(ListingInventoryProductOfferingTransformerInterface::class);
        $offeringTransformer->expects(self::once())->method('transform')
            ->with($offeringData)
            ->willReturn($offering);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ListingInventoryTransformerInterface::class), self::createStub(ListingInventoryProductTransformerInterface::class), $offeringTransformer);

        self::assertSame($offering, $api->getOffering(self::LISTING_ID, self::PRODUCT_ID, self::OFFERING_ID));
    }

    public function testGetOfferingSkipCacheRefetches(): void
    {
        $offering = self::createStub(ListingInventoryProductOfferingInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['offering-data']);

        $offeringTransformer = self::createStub(ListingInventoryProductOfferingTransformerInterface::class);
        $offeringTransformer->method('transform')->willReturn($offering);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), self::createStub(ListingInventoryProductTransformerInterface::class), $offeringTransformer);

        $first = $api->getOffering(self::LISTING_ID, self::PRODUCT_ID, self::OFFERING_ID, true);
        $second = $api->getOffering(self::LISTING_ID, self::PRODUCT_ID, self::OFFERING_ID, true);

        self::assertSame($offering, $first);
        self::assertSame($offering, $second);
    }

    public function testGetOfferingSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingInventoryApiInterface::UNEXPECTED_RESPONSE);

        $api->getOffering(self::LISTING_ID, self::PRODUCT_ID, self::OFFERING_ID, true);
    }

    public function testGetOfferingThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingInventoryApiInterface::UNEXPECTED_RESPONSE);

        $api->getOffering(self::LISTING_ID, self::PRODUCT_ID, self::OFFERING_ID);
    }

    public function testGetOfferingUsesCacheOnSecondCall(): void
    {
        $offering = self::createStub(ListingInventoryProductOfferingInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['offering-data']);

        $offeringTransformer = self::createStub(ListingInventoryProductOfferingTransformerInterface::class);
        $offeringTransformer->method('transform')->willReturn($offering);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), self::createStub(ListingInventoryProductTransformerInterface::class), $offeringTransformer);

        $first = $api->getOffering(self::LISTING_ID, self::PRODUCT_ID, self::OFFERING_ID);
        $second = $api->getOffering(self::LISTING_ID, self::PRODUCT_ID, self::OFFERING_ID);

        self::assertSame($offering, $first);
        self::assertSame($offering, $second);
    }

    public function testGetProductReturnsProduct(): void
    {
        $productData = ['product-data'];
        $headers = ['x-api-key' => 'key'];
        $product = self::createStub(ListingInventoryProductInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingInventoryApiInterface::API_URL_PRODUCT_SPRINTF, self::LISTING_ID, self::PRODUCT_ID),
                [],
                $headers,
            )
            ->willReturn($productData);

        $productTransformer = self::createMock(ListingInventoryProductTransformerInterface::class);
        $productTransformer->expects(self::once())->method('transform')
            ->with($productData)
            ->willReturn($product);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ListingInventoryTransformerInterface::class), $productTransformer, self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        self::assertSame($product, $api->getProduct(self::LISTING_ID, self::PRODUCT_ID));
    }

    public function testGetProductSkipCacheRefetches(): void
    {
        $product = self::createStub(ListingInventoryProductInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['product-data']);

        $productTransformer = self::createStub(ListingInventoryProductTransformerInterface::class);
        $productTransformer->method('transform')->willReturn($product);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), $productTransformer, self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $first = $api->getProduct(self::LISTING_ID, self::PRODUCT_ID, true);
        $second = $api->getProduct(self::LISTING_ID, self::PRODUCT_ID, true);

        self::assertSame($product, $first);
        self::assertSame($product, $second);
    }

    public function testGetProductSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingInventoryApiInterface::UNEXPECTED_RESPONSE);

        $api->getProduct(self::LISTING_ID, self::PRODUCT_ID, true);
    }

    public function testGetProductThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingInventoryApiInterface::UNEXPECTED_RESPONSE);

        $api->getProduct(self::LISTING_ID, self::PRODUCT_ID);
    }

    public function testGetProductUsesCacheOnSecondCall(): void
    {
        $product = self::createStub(ListingInventoryProductInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['product-data']);

        $productTransformer = self::createStub(ListingInventoryProductTransformerInterface::class);
        $productTransformer->method('transform')->willReturn($product);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), $productTransformer, self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $first = $api->getProduct(self::LISTING_ID, self::PRODUCT_ID);
        $second = $api->getProduct(self::LISTING_ID, self::PRODUCT_ID);

        self::assertSame($product, $first);
        self::assertSame($product, $second);
    }

    public function testUpdateReturnsInventory(): void
    {
        $headers = ['x-api-key' => 'key'];
        $inventoryData = ['inventory-self'];
        $inventory = self::createStub(ListingInventoryInterface::class);
        $serializedBody = ['products' => []];

        $request = self::createStub(UpdateListingInventoryRequestInterface::class);
        $requestSerializer = self::createMock(UpdateListingInventoryRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('put')
            ->with(
                sprintf(ListingInventoryApiInterface::API_URL_BY_LISTING_ID_SPRINTF, 11),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($inventoryData);

        $inventoryTransformer = self::createMock(ListingInventoryTransformerInterface::class);
        $inventoryTransformer->expects(self::once())->method('transform')
            ->with($inventoryData)
            ->willReturn($inventory);

        $api = $this->buildApi($headers, $requestSender, $inventoryTransformer, self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class), $requestSerializer);

        self::assertSame($inventory, $api->update(11, $request));
    }

    public function testUpdateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('put')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingInventoryTransformerInterface::class), self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingInventoryApiInterface::UNEXPECTED_RESPONSE);

        $api->update(11, self::createStub(UpdateListingInventoryRequestInterface::class));
    }

    public function testUpdateWithMaxVariationsSupportedPassesQuery(): void
    {
        $headers = ['x-api-key' => 'key'];
        $inventoryData = ['inventory-self'];
        $inventory = self::createStub(ListingInventoryInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('put')
            ->with(
                sprintf(ListingInventoryApiInterface::API_URL_BY_LISTING_ID_SPRINTF, 11),
                [ListingInventoryApiInterface::KEY_MAX_VARIATIONS_SUPPORTED => '3'],
                $headers,
                [],
            )
            ->willReturn($inventoryData);

        $inventoryTransformer = self::createStub(ListingInventoryTransformerInterface::class);
        $inventoryTransformer->method('transform')->willReturn($inventory);

        $api = $this->buildApi($headers, $requestSender, $inventoryTransformer, self::createStub(ListingInventoryProductTransformerInterface::class), self::createStub(ListingInventoryProductOfferingTransformerInterface::class));

        self::assertSame($inventory, $api->update(11, self::createStub(UpdateListingInventoryRequestInterface::class), '3'));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingInventoryTransformerInterface $inventoryTransformer, ListingInventoryProductTransformerInterface $productTransformer, ListingInventoryProductOfferingTransformerInterface $offeringTransformer, ?UpdateListingInventoryRequestSerializerInterface $updateListingInventoryRequestSerializer = null): ListingInventoryApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingInventoryApi($requestSender, $inventoryTransformer, $productTransformer, $offeringTransformer, $updateListingInventoryRequestSerializer ?? self::createStub(UpdateListingInventoryRequestSerializerInterface::class), new ResponseCache(), new ResponseCache(), new ResponseCache(), $credentials);
    }
}
