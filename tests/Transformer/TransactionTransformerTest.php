<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingPropertyValueInterface;
use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Model\Transaction;
use ChristianBrown\Etsy\Model\TransactionInterface;
use ChristianBrown\Etsy\Model\TransactionVariationInterface;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformerInterface;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformerInterface;
use ChristianBrown\Etsy\Transformer\TransactionVariationsTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(Transaction::class)]
#[CoversClass(TransactionTransformer::class)]
final class TransactionTransformerTest extends TestCase
{
    public function testSetTransactionId(): void
    {
        $transaction = new Transaction(1);

        self::assertSame(2, $transaction->setTransactionId(2)->getTransactionId());
    }

    public function testTransform(): void
    {
        $priceData = ['__price__'];
        $shippingCostData = ['__shipping_cost__'];
        $variationsData = ['__variations__'];
        $productDataData = ['__product_data__'];

        $data = [
            TransactionTransformerInterface::KEY_TRANSACTION_ID => 1000,
            TransactionTransformerInterface::KEY_TITLE => 'A title',
            TransactionTransformerInterface::KEY_DESCRIPTION => 'A description',
            TransactionTransformerInterface::KEY_SELLER_USER_ID => 11,
            TransactionTransformerInterface::KEY_BUYER_USER_ID => 12,
            TransactionTransformerInterface::KEY_CREATE_TIMESTAMP => 1600000001,
            TransactionTransformerInterface::KEY_CREATED_TIMESTAMP => 1600000002,
            TransactionTransformerInterface::KEY_PAID_TIMESTAMP => 1600000003,
            TransactionTransformerInterface::KEY_SHIPPED_TIMESTAMP => 1600000004,
            TransactionTransformerInterface::KEY_QUANTITY => 3,
            TransactionTransformerInterface::KEY_LISTING_IMAGE_ID => 21,
            TransactionTransformerInterface::KEY_RECEIPT_ID => 22,
            TransactionTransformerInterface::KEY_IS_DIGITAL => true,
            TransactionTransformerInterface::KEY_FILE_DATA => 'file',
            TransactionTransformerInterface::KEY_LISTING_ID => 23,
            TransactionTransformerInterface::KEY_TRANSACTION_TYPE => 'listing',
            TransactionTransformerInterface::KEY_PRODUCT_ID => 24,
            TransactionTransformerInterface::KEY_SKU => 'SKU1',
            TransactionTransformerInterface::KEY_PRICE => $priceData,
            TransactionTransformerInterface::KEY_SHIPPING_COST => $shippingCostData,
            TransactionTransformerInterface::KEY_VARIATIONS => $variationsData,
            TransactionTransformerInterface::KEY_PRODUCT_DATA => $productDataData,
            TransactionTransformerInterface::KEY_SHIPPING_PROFILE_ID => 25,
            TransactionTransformerInterface::KEY_MIN_PROCESSING_DAYS => 1,
            TransactionTransformerInterface::KEY_MAX_PROCESSING_DAYS => 5,
            TransactionTransformerInterface::KEY_SHIPPING_METHOD => 'method',
            TransactionTransformerInterface::KEY_SHIPPING_UPGRADE => 'upgrade',
            TransactionTransformerInterface::KEY_EXPECTED_SHIP_DATE => 1600000005,
            TransactionTransformerInterface::KEY_BUYER_COUPON => 1.5,
            TransactionTransformerInterface::KEY_SHOP_COUPON => 2,
        ];

        $price = self::createStub(MoneyInterface::class);
        $shippingCost = self::createStub(MoneyInterface::class);
        $variation = self::createStub(TransactionVariationInterface::class);
        $productData = self::createStub(ListingPropertyValueInterface::class);

        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);
        $moneyTransformer->method('transform')
            ->willReturnMap(
                [
                    [$priceData, $price],
                    [$shippingCostData, $shippingCost],
                ]
            );

        $variationsTransformer = self::createMock(TransactionVariationsTransformerInterface::class);
        $variationsTransformer->expects(self::once())->method('transform')
            ->with($variationsData)
            ->willReturn([$variation]);

        $listingPropertyValuesTransformer = self::createMock(ListingPropertyValuesTransformerInterface::class);
        $listingPropertyValuesTransformer->expects(self::once())->method('transform')
            ->with($productDataData)
            ->willReturn([$productData]);

        $transformer = new TransactionTransformer($moneyTransformer, $variationsTransformer, $listingPropertyValuesTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(1000, $actual->getTransactionId());
        self::assertSame('A title', $actual->getTitle());
        self::assertSame('A description', $actual->getDescription());
        self::assertSame(11, $actual->getSellerUserId());
        self::assertSame(12, $actual->getBuyerUserId());
        self::assertSame(1600000001, $actual->getCreateTimestamp());
        self::assertSame(1600000002, $actual->getCreatedTimestamp());
        self::assertSame(1600000003, $actual->getPaidTimestamp());
        self::assertSame(1600000004, $actual->getShippedTimestamp());
        self::assertSame(3, $actual->getQuantity());
        self::assertSame(21, $actual->getListingImageId());
        self::assertSame(22, $actual->getReceiptId());
        self::assertTrue($actual->getIsDigital());
        self::assertSame('file', $actual->getFileData());
        self::assertSame(23, $actual->getListingId());
        self::assertSame('listing', $actual->getTransactionType());
        self::assertSame(24, $actual->getProductId());
        self::assertSame('SKU1', $actual->getSku());
        self::assertSame($price, $actual->getPrice());
        self::assertSame($shippingCost, $actual->getShippingCost());
        self::assertSame([$variation], $actual->getVariations());
        self::assertSame([$productData], $actual->getProductData());
        self::assertSame(25, $actual->getShippingProfileId());
        self::assertSame(1, $actual->getMinProcessingDays());
        self::assertSame(5, $actual->getMaxProcessingDays());
        self::assertSame('method', $actual->getShippingMethod());
        self::assertSame('upgrade', $actual->getShippingUpgrade());
        self::assertSame(1600000005, $actual->getExpectedShipDate());
        self::assertSame(1.5, $actual->getBuyerCoupon());
        self::assertSame(2.0, $actual->getShopCoupon());
    }

    /**
     * Each case supplies the required transaction_id plus at most one optional
     * field in an absent / wrong-type / falsy-but-valid state, isolating the
     * early-return and set paths of that field's apply helper.
     *
     * @param array<string, mixed>                $data
     * @param Closure(TransactionInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new TransactionTransformer(
            self::createStub(MoneyTransformerInterface::class),
            self::createStub(TransactionVariationsTransformerInterface::class),
            self::createStub(ListingPropertyValuesTransformerInterface::class),
        );

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(TransactionInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = TransactionTransformerInterface::KEY_TRANSACTION_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (TransactionInterface $transaction): void {
                self::assertNull($transaction->getTitle());
                self::assertNull($transaction->getBuyerUserId());
                self::assertNull($transaction->getIsDigital());
                self::assertNull($transaction->getBuyerCoupon());
                self::assertNull($transaction->getPrice());
                self::assertNull($transaction->getShippingCost());
                self::assertSame([], $transaction->getVariations());
                self::assertSame([], $transaction->getProductData());
            },
        ];

        yield 'buyerUserIdWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_BUYER_USER_ID => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getBuyerUserId());
        }];
        yield 'createTimestampWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_CREATE_TIMESTAMP => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getCreateTimestamp());
        }];
        yield 'createTimestampZero' => [[$id => 1, TransactionTransformerInterface::KEY_CREATE_TIMESTAMP => 0], static function (TransactionInterface $t): void {
            self::assertSame(0, $t->getCreateTimestamp());
        }];
        yield 'createdTimestampWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getCreatedTimestamp());
        }];
        yield 'descriptionWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_DESCRIPTION => 42], static function (TransactionInterface $t): void {
            self::assertNull($t->getDescription());
        }];
        yield 'expectedShipDateWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_EXPECTED_SHIP_DATE => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getExpectedShipDate());
        }];
        yield 'fileDataWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_FILE_DATA => 42], static function (TransactionInterface $t): void {
            self::assertNull($t->getFileData());
        }];
        yield 'isDigitalWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_IS_DIGITAL => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getIsDigital());
        }];
        yield 'isDigitalFalse' => [[$id => 1, TransactionTransformerInterface::KEY_IS_DIGITAL => false], static function (TransactionInterface $t): void {
            self::assertFalse($t->getIsDigital());
        }];
        yield 'listingIdWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_LISTING_ID => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getListingId());
        }];
        yield 'listingImageIdWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_LISTING_IMAGE_ID => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getListingImageId());
        }];
        yield 'maxProcessingDaysWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_MAX_PROCESSING_DAYS => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getMaxProcessingDays());
        }];
        yield 'minProcessingDaysWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_MIN_PROCESSING_DAYS => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getMinProcessingDays());
        }];
        yield 'paidTimestampWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_PAID_TIMESTAMP => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getPaidTimestamp());
        }];
        yield 'productIdWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_PRODUCT_ID => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getProductId());
        }];
        yield 'quantityWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_QUANTITY => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getQuantity());
        }];
        yield 'quantityZero' => [[$id => 1, TransactionTransformerInterface::KEY_QUANTITY => 0], static function (TransactionInterface $t): void {
            self::assertSame(0, $t->getQuantity());
        }];
        yield 'receiptIdWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_RECEIPT_ID => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getReceiptId());
        }];
        yield 'sellerUserIdWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_SELLER_USER_ID => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getSellerUserId());
        }];
        yield 'shippedTimestampWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_SHIPPED_TIMESTAMP => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getShippedTimestamp());
        }];
        yield 'shippingMethodWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_SHIPPING_METHOD => 42], static function (TransactionInterface $t): void {
            self::assertNull($t->getShippingMethod());
        }];
        yield 'shippingProfileIdWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_SHIPPING_PROFILE_ID => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getShippingProfileId());
        }];
        yield 'shippingUpgradeWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_SHIPPING_UPGRADE => 42], static function (TransactionInterface $t): void {
            self::assertNull($t->getShippingUpgrade());
        }];
        yield 'skuWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_SKU => 42], static function (TransactionInterface $t): void {
            self::assertNull($t->getSku());
        }];
        yield 'titleWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_TITLE => 42], static function (TransactionInterface $t): void {
            self::assertNull($t->getTitle());
        }];
        yield 'transactionTypeWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_TRANSACTION_TYPE => 42], static function (TransactionInterface $t): void {
            self::assertNull($t->getTransactionType());
        }];
        yield 'buyerCouponWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_BUYER_COUPON => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getBuyerCoupon());
        }];
        yield 'shopCouponWrongType' => [[$id => 1, TransactionTransformerInterface::KEY_SHOP_COUPON => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getShopCoupon());
        }];
        yield 'priceNonArray' => [[$id => 1, TransactionTransformerInterface::KEY_PRICE => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getPrice());
        }];
        yield 'shippingCostNonArray' => [[$id => 1, TransactionTransformerInterface::KEY_SHIPPING_COST => 'x'], static function (TransactionInterface $t): void {
            self::assertNull($t->getShippingCost());
        }];
        yield 'variationsNonArray' => [[$id => 1, TransactionTransformerInterface::KEY_VARIATIONS => 'x'], static function (TransactionInterface $t): void {
            self::assertSame([], $t->getVariations());
        }];
        yield 'productDataNonArray' => [[$id => 1, TransactionTransformerInterface::KEY_PRODUCT_DATA => 'x'], static function (TransactionInterface $t): void {
            self::assertSame([], $t->getProductData());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[TransactionTransformerInterface::KEY_TRANSACTION_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidTransactionId(array $data): void
    {
        $transformer = new TransactionTransformer(
            self::createStub(MoneyTransformerInterface::class),
            self::createStub(TransactionVariationsTransformerInterface::class),
            self::createStub(ListingPropertyValuesTransformerInterface::class),
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(TransactionTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, TransactionTransformerInterface::KEY_TRANSACTION_ID));

        $transformer->transform($data);
    }
}
