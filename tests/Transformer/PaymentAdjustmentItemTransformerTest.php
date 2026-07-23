<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\PaymentAdjustmentItem;
use ChristianBrown\Etsy\Model\PaymentAdjustmentItemInterface;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(PaymentAdjustmentItem::class)]
#[CoversClass(PaymentAdjustmentItemTransformer::class)]
final class PaymentAdjustmentItemTransformerTest extends TestCase
{
    public function testSetPaymentAdjustmentItemId(): void
    {
        $item = new PaymentAdjustmentItem();

        self::assertSame(2, $item->setPaymentAdjustmentItemId(2)->getPaymentAdjustmentItemId());
    }

    public function testTransform(): void
    {
        $data = [
            PaymentAdjustmentItemTransformerInterface::KEY_PAYMENT_ADJUSTMENT_ID => 10,
            PaymentAdjustmentItemTransformerInterface::KEY_PAYMENT_ADJUSTMENT_ITEM_ID => 11,
            PaymentAdjustmentItemTransformerInterface::KEY_ADJUSTMENT_TYPE => 'shipping',
            PaymentAdjustmentItemTransformerInterface::KEY_AMOUNT => -12,
            PaymentAdjustmentItemTransformerInterface::KEY_SHOP_AMOUNT => -13,
            PaymentAdjustmentItemTransformerInterface::KEY_TRANSACTION_ID => 14,
            PaymentAdjustmentItemTransformerInterface::KEY_BILL_PAYMENT_ID => 15,
            PaymentAdjustmentItemTransformerInterface::KEY_CREATED_TIMESTAMP => 1600000001,
            PaymentAdjustmentItemTransformerInterface::KEY_UPDATED_TIMESTAMP => 1600000002,
        ];

        $transformer = new PaymentAdjustmentItemTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(10, $actual->getPaymentAdjustmentId());
        self::assertSame(11, $actual->getPaymentAdjustmentItemId());
        self::assertSame('shipping', $actual->getAdjustmentType());
        self::assertSame(-12, $actual->getAmount());
        self::assertSame(-13, $actual->getShopAmount());
        self::assertSame(14, $actual->getTransactionId());
        self::assertSame(15, $actual->getBillPaymentId());
        self::assertSame(1600000001, $actual->getCreatedTimestamp());
        self::assertSame(1600000002, $actual->getUpdatedTimestamp());
    }

    /**
     * @param array<string, mixed>                          $data
     * @param Closure(PaymentAdjustmentItemInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new PaymentAdjustmentItemTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(PaymentAdjustmentItemInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        yield 'allOptionalAbsent' => [
            [],
            static function (PaymentAdjustmentItemInterface $item): void {
                self::assertNull($item->getPaymentAdjustmentId());
                self::assertNull($item->getPaymentAdjustmentItemId());
                self::assertNull($item->getAdjustmentType());
                self::assertNull($item->getAmount());
                self::assertNull($item->getShopAmount());
                self::assertNull($item->getTransactionId());
                self::assertNull($item->getBillPaymentId());
                self::assertNull($item->getCreatedTimestamp());
                self::assertNull($item->getUpdatedTimestamp());
            },
        ];

        yield 'adjustmentTypeWrongType' => [[PaymentAdjustmentItemTransformerInterface::KEY_ADJUSTMENT_TYPE => 42], static function (PaymentAdjustmentItemInterface $i): void {
            self::assertNull($i->getAdjustmentType());
        }];
        yield 'amountWrongType' => [[PaymentAdjustmentItemTransformerInterface::KEY_AMOUNT => 'x'], static function (PaymentAdjustmentItemInterface $i): void {
            self::assertNull($i->getAmount());
        }];
        yield 'amountZero' => [[PaymentAdjustmentItemTransformerInterface::KEY_AMOUNT => 0], static function (PaymentAdjustmentItemInterface $i): void {
            self::assertSame(0, $i->getAmount());
        }];
        yield 'billPaymentIdWrongType' => [[PaymentAdjustmentItemTransformerInterface::KEY_BILL_PAYMENT_ID => 'x'], static function (PaymentAdjustmentItemInterface $i): void {
            self::assertNull($i->getBillPaymentId());
        }];
        yield 'createdTimestampWrongType' => [[PaymentAdjustmentItemTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (PaymentAdjustmentItemInterface $i): void {
            self::assertNull($i->getCreatedTimestamp());
        }];
        yield 'paymentAdjustmentIdWrongType' => [[PaymentAdjustmentItemTransformerInterface::KEY_PAYMENT_ADJUSTMENT_ID => 'x'], static function (PaymentAdjustmentItemInterface $i): void {
            self::assertNull($i->getPaymentAdjustmentId());
        }];
        yield 'paymentAdjustmentItemIdWrongType' => [[PaymentAdjustmentItemTransformerInterface::KEY_PAYMENT_ADJUSTMENT_ITEM_ID => 'x'], static function (PaymentAdjustmentItemInterface $i): void {
            self::assertNull($i->getPaymentAdjustmentItemId());
        }];
        yield 'shopAmountWrongType' => [[PaymentAdjustmentItemTransformerInterface::KEY_SHOP_AMOUNT => 'x'], static function (PaymentAdjustmentItemInterface $i): void {
            self::assertNull($i->getShopAmount());
        }];
        yield 'transactionIdWrongType' => [[PaymentAdjustmentItemTransformerInterface::KEY_TRANSACTION_ID => 'x'], static function (PaymentAdjustmentItemInterface $i): void {
            self::assertNull($i->getTransactionId());
        }];
        yield 'updatedTimestampWrongType' => [[PaymentAdjustmentItemTransformerInterface::KEY_UPDATED_TIMESTAMP => 'x'], static function (PaymentAdjustmentItemInterface $i): void {
            self::assertNull($i->getUpdatedTimestamp());
        }];
    }
}
