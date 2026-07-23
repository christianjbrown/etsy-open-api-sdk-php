<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\ListingInterface;
use ChristianBrown\Etsy\Model\ListingInventory;
use ChristianBrown\Etsy\Model\ListingInventoryInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingInventory::class)]
#[CoversClass(ListingInventoryTransformer::class)]
final class ListingInventoryTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $productsData = [['__product__']];
        $products = [self::createStub(ListingInventoryProductInterface::class)];
        $listingData = ['__listing__'];
        $listing = self::createStub(ListingInterface::class);

        $data = [
            ListingInventoryTransformerInterface::KEY_PRODUCTS => $productsData,
            ListingInventoryTransformerInterface::KEY_PRICE_ON_PROPERTY => [1, 'skip-me', 2],
            ListingInventoryTransformerInterface::KEY_QUANTITY_ON_PROPERTY => [3],
            ListingInventoryTransformerInterface::KEY_SKU_ON_PROPERTY => [4],
            ListingInventoryTransformerInterface::KEY_READINESS_STATE_ON_PROPERTY => [5],
            ListingInventoryTransformerInterface::KEY_LISTING => $listingData,
        ];

        $productsTransformer = self::createMock(ListingInventoryProductsTransformerInterface::class);
        $productsTransformer->expects(self::once())->method('transform')
            ->with($productsData)
            ->willReturn($products);

        $listingTransformer = self::createMock(ListingTransformerInterface::class);
        $listingTransformer->expects(self::once())->method('transform')
            ->with($listingData)
            ->willReturn($listing);

        $transformer = new ListingInventoryTransformer($productsTransformer, $listingTransformer);

        $actual = $transformer->transform($data);

        self::assertSame($products, $actual->getProducts());
        self::assertSame([1, 2], $actual->getPriceOnProperty());
        self::assertSame([3], $actual->getQuantityOnProperty());
        self::assertSame([4], $actual->getSkuOnProperty());
        self::assertSame([5], $actual->getReadinessStateOnProperty());
        self::assertSame($listing, $actual->getListing());
    }

    /**
     * @param array<string, mixed>                     $data
     * @param Closure(ListingInventoryInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $productsTransformer = self::createStub(ListingInventoryProductsTransformerInterface::class);
        $listingTransformer = self::createStub(ListingTransformerInterface::class);

        $transformer = new ListingInventoryTransformer($productsTransformer, $listingTransformer);

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ListingInventoryInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        yield 'allOptionalAbsent' => [
            [],
            static function (ListingInventoryInterface $m): void {
                self::assertSame([], $m->getProducts());
                self::assertSame([], $m->getPriceOnProperty());
                self::assertSame([], $m->getQuantityOnProperty());
                self::assertSame([], $m->getSkuOnProperty());
                self::assertSame([], $m->getReadinessStateOnProperty());
                self::assertNull($m->getListing());
            },
        ];

        yield 'productsEmpty' => [[ListingInventoryTransformerInterface::KEY_PRODUCTS => []], static function (ListingInventoryInterface $m): void {
            self::assertSame([], $m->getProducts());
        }];
        yield 'productsWrongType' => [[ListingInventoryTransformerInterface::KEY_PRODUCTS => 'x'], static function (ListingInventoryInterface $m): void {
            self::assertSame([], $m->getProducts());
        }];
        yield 'priceOnPropertyWrongType' => [[ListingInventoryTransformerInterface::KEY_PRICE_ON_PROPERTY => 'x'], static function (ListingInventoryInterface $m): void {
            self::assertSame([], $m->getPriceOnProperty());
        }];
        yield 'priceOnPropertyEmptyArray' => [[ListingInventoryTransformerInterface::KEY_PRICE_ON_PROPERTY => []], static function (ListingInventoryInterface $m): void {
            self::assertSame([], $m->getPriceOnProperty());
        }];
        yield 'priceOnPropertyAllInvalid' => [[ListingInventoryTransformerInterface::KEY_PRICE_ON_PROPERTY => ['a', 'b']], static function (ListingInventoryInterface $m): void {
            self::assertSame([], $m->getPriceOnProperty());
        }];
        yield 'quantityOnPropertyWrongType' => [[ListingInventoryTransformerInterface::KEY_QUANTITY_ON_PROPERTY => 'x'], static function (ListingInventoryInterface $m): void {
            self::assertSame([], $m->getQuantityOnProperty());
        }];
        yield 'skuOnPropertyWrongType' => [[ListingInventoryTransformerInterface::KEY_SKU_ON_PROPERTY => 'x'], static function (ListingInventoryInterface $m): void {
            self::assertSame([], $m->getSkuOnProperty());
        }];
        yield 'readinessStateOnPropertyWrongType' => [[ListingInventoryTransformerInterface::KEY_READINESS_STATE_ON_PROPERTY => 'x'], static function (ListingInventoryInterface $m): void {
            self::assertSame([], $m->getReadinessStateOnProperty());
        }];
        yield 'listingEmpty' => [[ListingInventoryTransformerInterface::KEY_LISTING => []], static function (ListingInventoryInterface $m): void {
            self::assertNull($m->getListing());
        }];
        yield 'listingWrongType' => [[ListingInventoryTransformerInterface::KEY_LISTING => 'x'], static function (ListingInventoryInterface $m): void {
            self::assertNull($m->getListing());
        }];
    }
}
