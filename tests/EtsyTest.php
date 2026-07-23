<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests;

use ChristianBrown\Etsy\Api\PingApi;
use ChristianBrown\Etsy\Api\PingApiInterface;
use ChristianBrown\Etsy\Api\ShopApi;
use ChristianBrown\Etsy\Api\ShopApiInterface;
use ChristianBrown\Etsy\Api\ShopReceiptApi;
use ChristianBrown\Etsy\Api\ShopReceiptApiInterface;
use ChristianBrown\Etsy\Api\UserAddressApi;
use ChristianBrown\Etsy\Api\UserAddressApiInterface;
use ChristianBrown\Etsy\Api\UserApi;
use ChristianBrown\Etsy\Api\UserApiInterface;
use ChristianBrown\Etsy\Auth\Credentials;
use ChristianBrown\Etsy\Etsy;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\PingTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\RefundsTransformer;
use ChristianBrown\Etsy\Transformer\RefundTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentsTransformer;
use ChristianBrown\Etsy\Transformer\ShopsTransformer;
use ChristianBrown\Etsy\Transformer\ShopTransformer;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationsTransformer;
use ChristianBrown\Etsy\Transformer\UserAddressesTransformer;
use ChristianBrown\Etsy\Transformer\UserAddressTransformer;
use ChristianBrown\Etsy\Transformer\UserTransformer;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\KeyValueStore\TtlAwareKeyValueStoreInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Etsy::class)]
#[UsesClass(PingApi::class)]
#[UsesClass(ShopApi::class)]
#[UsesClass(ShopReceiptApi::class)]
#[UsesClass(UserApi::class)]
#[UsesClass(UserAddressApi::class)]
#[UsesClass(Credentials::class)]
#[UsesClass(ListingPropertyValuesTransformer::class)]
#[UsesClass(PingTransformer::class)]
#[UsesClass(ReceiptTransformer::class)]
#[UsesClass(ReceiptsTransformer::class)]
#[UsesClass(RefundTransformer::class)]
#[UsesClass(RefundsTransformer::class)]
#[UsesClass(ShipmentsTransformer::class)]
#[UsesClass(ShopTransformer::class)]
#[UsesClass(ShopsTransformer::class)]
#[UsesClass(TransactionTransformer::class)]
#[UsesClass(TransactionsTransformer::class)]
#[UsesClass(TransactionVariationsTransformer::class)]
#[UsesClass(UserTransformer::class)]
#[UsesClass(UserAddressTransformer::class)]
#[UsesClass(UserAddressesTransformer::class)]
final class EtsyTest extends TestCase
{
    public function testGetPingApi(): void
    {
        self::assertInstanceOf(PingApiInterface::class, $this->buildEtsy()->getPingApi());
    }

    public function testGetPingApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getPingApi(), $etsy->getPingApi());
    }

    public function testGetShopApi(): void
    {
        self::assertInstanceOf(ShopApiInterface::class, $this->buildEtsy()->getShopApi());
    }

    public function testGetShopApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopApi(), $etsy->getShopApi());
    }

    public function testGetShopReceiptApi(): void
    {
        self::assertInstanceOf(ShopReceiptApiInterface::class, $this->buildEtsy()->getShopReceiptApi());
    }

    public function testGetShopReceiptApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getShopReceiptApi(), $etsy->getShopReceiptApi());
    }

    public function testGetUserAddressApi(): void
    {
        self::assertInstanceOf(UserAddressApiInterface::class, $this->buildEtsy()->getUserAddressApi());
    }

    public function testGetUserAddressApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getUserAddressApi(), $etsy->getUserAddressApi());
    }

    public function testGetUserApi(): void
    {
        self::assertInstanceOf(UserApiInterface::class, $this->buildEtsy()->getUserApi());
    }

    public function testGetUserApiReturnsSharedInstance(): void
    {
        $etsy = $this->buildEtsy();

        self::assertSame($etsy->getUserApi(), $etsy->getUserApi());
    }

    private function buildEtsy(): Etsy
    {
        return new Etsy(
            42,
            'test-keystring',
            self::createStub(TtlAwareKeyValueStoreInterface::class),
            self::createStub(KeyValueStoreInterface::class),
        );
    }
}
