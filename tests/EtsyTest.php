<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests;

use ChristianBrown\Etsy\Api\ShopReceiptApi;
use ChristianBrown\Etsy\Api\ShopReceiptApiInterface;
use ChristianBrown\Etsy\Auth\Credentials;
use ChristianBrown\Etsy\Etsy;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\RefundsTransformer;
use ChristianBrown\Etsy\Transformer\RefundTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationsTransformer;
use ChristianBrown\KeyValueStore\KeyValueStoreInterface;
use ChristianBrown\KeyValueStore\TtlAwareKeyValueStoreInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Etsy::class)]
#[UsesClass(ShopReceiptApi::class)]
#[UsesClass(Credentials::class)]
#[UsesClass(ListingPropertyValuesTransformer::class)]
#[UsesClass(ReceiptTransformer::class)]
#[UsesClass(ReceiptsTransformer::class)]
#[UsesClass(RefundTransformer::class)]
#[UsesClass(RefundsTransformer::class)]
#[UsesClass(ShipmentsTransformer::class)]
#[UsesClass(TransactionTransformer::class)]
#[UsesClass(TransactionsTransformer::class)]
#[UsesClass(TransactionVariationsTransformer::class)]
final class EtsyTest extends TestCase
{
    public function testGetShopReceiptApi(): void
    {
        $etsy = new Etsy(
            42,
            'test-keystring',
            self::createStub(TtlAwareKeyValueStoreInterface::class),
            self::createStub(KeyValueStoreInterface::class),
        );

        $api = $etsy->getShopReceiptApi();

        self::assertInstanceOf(ShopReceiptApiInterface::class, $api);
    }

    public function testGetShopReceiptApiReturnsSharedInstance(): void
    {
        $etsy = new Etsy(
            42,
            'test-keystring',
            self::createStub(TtlAwareKeyValueStoreInterface::class),
            self::createStub(KeyValueStoreInterface::class),
        );

        self::assertSame($etsy->getShopReceiptApi(), $etsy->getShopReceiptApi());
    }
}
