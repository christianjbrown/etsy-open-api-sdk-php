<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopApi;
use ChristianBrown\Etsy\Api\ShopApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCache;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopInterface;
use ChristianBrown\Etsy\Model\UpdateShopRequestInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ShopsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopApi::class)]
#[UsesClass(ResponseCache::class)]
final class ShopApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testFindByNameReturnsShops(): void
    {
        $resultsData = [['shop-1'], ['shop-2']];
        $headers = ['x-api-key' => 'key'];
        $shops = [self::createStub(ShopInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                ShopApiInterface::API_URL_FIND,
                [
                    ShopApiInterface::KEY_SHOP_NAME => 'CoolShop',
                    ShopApiInterface::KEY_LIMIT => '10',
                    ShopApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ShopApiInterface::KEY_RESULTS => $resultsData]);

        $shopsTransformer = self::createMock(ShopsTransformerInterface::class);
        $shopsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($shops);

        $api = $this->buildApi($headers, $requestSender, self::createStub(ShopTransformerInterface::class), $shopsTransformer);

        self::assertSame($shops, $api->findByName('CoolShop', 10, 5));
    }

    public function testFindByNameSkipCacheRefetches(): void
    {
        $shops = [self::createStub(ShopInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopApiInterface::KEY_RESULTS => [['shop-1']]]);

        $shopsTransformer = self::createStub(ShopsTransformerInterface::class);
        $shopsTransformer->method('transform')->willReturn($shops);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), $shopsTransformer);

        $first = $api->findByName('CoolShop', 25, 0, true);
        $second = $api->findByName('CoolShop', 25, 0, true);

        self::assertSame($shops, $first);
        self::assertSame($shops, $second);
    }

    public function testFindByNameSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), self::createStub(ShopsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopApiInterface::KEY_RESULTS));

        $api->findByName('CoolShop', 25, 0, true);
    }

    public function testFindByNameSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), self::createStub(ShopsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopApiInterface::KEY_RESULTS));

        $api->findByName('CoolShop', 25, 0, true);
    }

    public function testFindByNameThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), self::createStub(ShopsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopApiInterface::KEY_RESULTS));

        $api->findByName('CoolShop');
    }

    public function testFindByNameThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), self::createStub(ShopsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopApiInterface::KEY_RESULTS));

        $api->findByName('CoolShop');
    }

    public function testFindByNameUsesCacheOnSecondCall(): void
    {
        $shops = [self::createStub(ShopInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopApiInterface::KEY_RESULTS => [['shop-1']]]);

        $shopsTransformer = self::createStub(ShopsTransformerInterface::class);
        $shopsTransformer->method('transform')->willReturn($shops);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), $shopsTransformer);

        $first = $api->findByName('CoolShop');
        $second = $api->findByName('CoolShop');

        self::assertSame($shops, $first);
        self::assertSame($shops, $second);
    }

    public function testGetByOwnerUserIdReturnsShop(): void
    {
        $shopData = ['shop-owner'];
        $headers = ['x-api-key' => 'key'];
        $shop = self::createStub(ShopInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopApiInterface::API_URL_BY_OWNER_SPRINTF, 77),
                [],
                $headers,
            )
            ->willReturn($shopData);

        $shopTransformer = self::createMock(ShopTransformerInterface::class);
        $shopTransformer->expects(self::once())->method('transform')
            ->with($shopData)
            ->willReturn($shop);

        $api = $this->buildApi($headers, $requestSender, $shopTransformer, self::createStub(ShopsTransformerInterface::class));

        self::assertSame($shop, $api->getByOwnerUserId(77));
    }

    public function testGetByOwnerUserIdSkipCacheRefetches(): void
    {
        $shop = self::createStub(ShopInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['shop-owner']);

        $shopTransformer = self::createStub(ShopTransformerInterface::class);
        $shopTransformer->method('transform')->willReturn($shop);

        $api = $this->buildApi([], $requestSender, $shopTransformer, self::createStub(ShopsTransformerInterface::class));

        $first = $api->getByOwnerUserId(77, true);
        $second = $api->getByOwnerUserId(77, true);

        self::assertSame($shop, $first);
        self::assertSame($shop, $second);
    }

    public function testGetByOwnerUserIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), self::createStub(ShopsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopApiInterface::UNEXPECTED_RESPONSE);

        $api->getByOwnerUserId(77, true);
    }

    public function testGetByOwnerUserIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), self::createStub(ShopsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopApiInterface::UNEXPECTED_RESPONSE);

        $api->getByOwnerUserId(77);
    }

    public function testGetByOwnerUserIdUsesCacheOnSecondCall(): void
    {
        $shop = self::createStub(ShopInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['shop-owner']);

        $shopTransformer = self::createStub(ShopTransformerInterface::class);
        $shopTransformer->method('transform')->willReturn($shop);

        $api = $this->buildApi([], $requestSender, $shopTransformer, self::createStub(ShopsTransformerInterface::class));

        $first = $api->getByOwnerUserId(77);
        $second = $api->getByOwnerUserId(77);

        self::assertSame($shop, $first);
        self::assertSame($shop, $second);
    }

    public function testGetShopReturnsShop(): void
    {
        $shopData = ['shop-self'];
        $headers = ['x-api-key' => 'key'];
        $shop = self::createStub(ShopInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopApiInterface::API_URL_SHOP_SPRINTF, self::SHOP_ID),
                [],
                $headers,
            )
            ->willReturn($shopData);

        $shopTransformer = self::createMock(ShopTransformerInterface::class);
        $shopTransformer->expects(self::once())->method('transform')
            ->with($shopData)
            ->willReturn($shop);

        $api = $this->buildApi($headers, $requestSender, $shopTransformer, self::createStub(ShopsTransformerInterface::class));

        self::assertSame($shop, $api->getShop());
    }

    public function testGetShopSkipCacheRefetches(): void
    {
        $shop = self::createStub(ShopInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['shop-self']);

        $shopTransformer = self::createStub(ShopTransformerInterface::class);
        $shopTransformer->method('transform')->willReturn($shop);

        $api = $this->buildApi([], $requestSender, $shopTransformer, self::createStub(ShopsTransformerInterface::class));

        $first = $api->getShop(true);
        $second = $api->getShop(true);

        self::assertSame($shop, $first);
        self::assertSame($shop, $second);
    }

    public function testGetShopSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), self::createStub(ShopsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopApiInterface::UNEXPECTED_RESPONSE);

        $api->getShop(true);
    }

    public function testGetShopThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), self::createStub(ShopsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopApiInterface::UNEXPECTED_RESPONSE);

        $api->getShop();
    }

    public function testGetShopUsesCacheOnSecondCall(): void
    {
        $shop = self::createStub(ShopInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['shop-self']);

        $shopTransformer = self::createStub(ShopTransformerInterface::class);
        $shopTransformer->method('transform')->willReturn($shop);

        $api = $this->buildApi([], $requestSender, $shopTransformer, self::createStub(ShopsTransformerInterface::class));

        $first = $api->getShop();
        $second = $api->getShop();

        self::assertSame($shop, $first);
        self::assertSame($shop, $second);
    }

    public function testUpdateShopReturnsShop(): void
    {
        $headers = ['x-api-key' => 'key'];
        $shopData = ['shop-self'];
        $shop = self::createStub(ShopInterface::class);
        $serializedBody = ['title' => 'New title'];

        $request = self::createStub(UpdateShopRequestInterface::class);
        $requestSerializer = self::createMock(UpdateShopRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('putForm')
            ->with(
                sprintf(ShopApiInterface::API_URL_SHOP_SPRINTF, self::SHOP_ID),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($shopData);

        $shopTransformer = self::createMock(ShopTransformerInterface::class);
        $shopTransformer->expects(self::once())->method('transform')
            ->with($shopData)
            ->willReturn($shop);

        $api = $this->buildApi($headers, $requestSender, $shopTransformer, self::createStub(ShopsTransformerInterface::class), $requestSerializer);

        self::assertSame($shop, $api->updateShop($request));
    }

    public function testUpdateShopThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('putForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopTransformerInterface::class), self::createStub(ShopsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopApiInterface::UNEXPECTED_RESPONSE);

        $api->updateShop(self::createStub(UpdateShopRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ShopTransformerInterface $shopTransformer, ShopsTransformerInterface $shopsTransformer, ?UpdateShopRequestSerializerInterface $updateShopRequestSerializer = null): ShopApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopApi($requestSender, $shopTransformer, $shopsTransformer, $updateShopRequestSerializer ?? self::createStub(UpdateShopRequestSerializerInterface::class), new ResponseCache(), new ResponseCache(), $credentials, self::SHOP_ID);
    }
}
