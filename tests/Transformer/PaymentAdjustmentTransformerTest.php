<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAdjustment;
use ChristianBrown\Etsy\Model\PaymentAdjustmentInterface;
use ChristianBrown\Etsy\Model\PaymentAdjustmentItemInterface;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentItemsTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(PaymentAdjustment::class)]
#[CoversClass(PaymentAdjustmentTransformer::class)]
final class PaymentAdjustmentTransformerTest extends TestCase
{
    public function testSetPaymentAdjustmentId(): void
    {
        $adjustment = new PaymentAdjustment(1);

        self::assertSame(2, $adjustment->setPaymentAdjustmentId(2)->getPaymentAdjustmentId());
    }

    public function testTransform(): void
    {
        $itemsData = ['__payment_adjustment_items__'];

        $data = [
            PaymentAdjustmentTransformerInterface::KEY_PAYMENT_ADJUSTMENT_ID => 1000,
            PaymentAdjustmentTransformerInterface::KEY_PAYMENT_ID => 11,
            PaymentAdjustmentTransformerInterface::KEY_STATUS => 'complete',
            PaymentAdjustmentTransformerInterface::KEY_IS_SUCCESS => true,
            PaymentAdjustmentTransformerInterface::KEY_USER_ID => 12,
            PaymentAdjustmentTransformerInterface::KEY_REASON_CODE => 'buyer_cancel',
            PaymentAdjustmentTransformerInterface::KEY_TOTAL_ADJUSTMENT_AMOUNT => 13,
            PaymentAdjustmentTransformerInterface::KEY_SHOP_TOTAL_ADJUSTMENT_AMOUNT => 14,
            PaymentAdjustmentTransformerInterface::KEY_BUYER_TOTAL_ADJUSTMENT_AMOUNT => 15,
            PaymentAdjustmentTransformerInterface::KEY_TOTAL_FEE_ADJUSTMENT_AMOUNT => 16,
            PaymentAdjustmentTransformerInterface::KEY_CREATE_TIMESTAMP => 1600000001,
            PaymentAdjustmentTransformerInterface::KEY_CREATED_TIMESTAMP => 1600000002,
            PaymentAdjustmentTransformerInterface::KEY_UPDATE_TIMESTAMP => 1600000003,
            PaymentAdjustmentTransformerInterface::KEY_UPDATED_TIMESTAMP => 1600000004,
            PaymentAdjustmentTransformerInterface::KEY_PAYMENT_ADJUSTMENT_ITEMS => $itemsData,
        ];

        $item = self::createStub(PaymentAdjustmentItemInterface::class);
        $itemsTransformer = self::createMock(PaymentAdjustmentItemsTransformerInterface::class);
        $itemsTransformer->expects(self::once())->method('transform')
            ->with($itemsData)
            ->willReturn([$item]);

        $transformer = new PaymentAdjustmentTransformer($itemsTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(1000, $actual->getPaymentAdjustmentId());
        self::assertSame(11, $actual->getPaymentId());
        self::assertSame('complete', $actual->getStatus());
        self::assertTrue($actual->getIsSuccess());
        self::assertSame(12, $actual->getUserId());
        self::assertSame('buyer_cancel', $actual->getReasonCode());
        self::assertSame(13, $actual->getTotalAdjustmentAmount());
        self::assertSame(14, $actual->getShopTotalAdjustmentAmount());
        self::assertSame(15, $actual->getBuyerTotalAdjustmentAmount());
        self::assertSame(16, $actual->getTotalFeeAdjustmentAmount());
        self::assertSame(1600000001, $actual->getCreateTimestamp());
        self::assertSame(1600000002, $actual->getCreatedTimestamp());
        self::assertSame(1600000003, $actual->getUpdateTimestamp());
        self::assertSame(1600000004, $actual->getUpdatedTimestamp());
        self::assertSame([$item], $actual->getPaymentAdjustmentItems());
    }

    /**
     * @param array<string, mixed>                      $data
     * @param Closure(PaymentAdjustmentInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new PaymentAdjustmentTransformer(self::createStub(PaymentAdjustmentItemsTransformerInterface::class));

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(PaymentAdjustmentInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = PaymentAdjustmentTransformerInterface::KEY_PAYMENT_ADJUSTMENT_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (PaymentAdjustmentInterface $adjustment): void {
                self::assertNull($adjustment->getPaymentId());
                self::assertNull($adjustment->getStatus());
                self::assertNull($adjustment->getIsSuccess());
                self::assertNull($adjustment->getReasonCode());
                self::assertNull($adjustment->getTotalAdjustmentAmount());
                self::assertSame([], $adjustment->getPaymentAdjustmentItems());
            },
        ];

        yield 'buyerTotalAdjustmentAmountWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_BUYER_TOTAL_ADJUSTMENT_AMOUNT => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getBuyerTotalAdjustmentAmount());
        }];
        yield 'createdTimestampWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getCreatedTimestamp());
        }];
        yield 'createTimestampWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_CREATE_TIMESTAMP => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getCreateTimestamp());
        }];
        yield 'isSuccessWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_IS_SUCCESS => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getIsSuccess());
        }];
        yield 'isSuccessFalse' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_IS_SUCCESS => false], static function (PaymentAdjustmentInterface $a): void {
            self::assertFalse($a->getIsSuccess());
        }];
        yield 'paymentIdWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_PAYMENT_ID => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getPaymentId());
        }];
        yield 'reasonCodeWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_REASON_CODE => 42], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getReasonCode());
        }];
        yield 'shopTotalAdjustmentAmountWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_SHOP_TOTAL_ADJUSTMENT_AMOUNT => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getShopTotalAdjustmentAmount());
        }];
        yield 'statusWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_STATUS => 42], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getStatus());
        }];
        yield 'totalAdjustmentAmountWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_TOTAL_ADJUSTMENT_AMOUNT => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getTotalAdjustmentAmount());
        }];
        yield 'totalFeeAdjustmentAmountWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_TOTAL_FEE_ADJUSTMENT_AMOUNT => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getTotalFeeAdjustmentAmount());
        }];
        yield 'updatedTimestampWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_UPDATED_TIMESTAMP => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getUpdatedTimestamp());
        }];
        yield 'updateTimestampWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_UPDATE_TIMESTAMP => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getUpdateTimestamp());
        }];
        yield 'userIdWrongType' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_USER_ID => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertNull($a->getUserId());
        }];
        yield 'paymentAdjustmentItemsNonArray' => [[$id => 1, PaymentAdjustmentTransformerInterface::KEY_PAYMENT_ADJUSTMENT_ITEMS => 'x'], static function (PaymentAdjustmentInterface $a): void {
            self::assertSame([], $a->getPaymentAdjustmentItems());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[PaymentAdjustmentTransformerInterface::KEY_PAYMENT_ADJUSTMENT_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidPaymentAdjustmentId(array $data): void
    {
        $transformer = new PaymentAdjustmentTransformer(self::createStub(PaymentAdjustmentItemsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PaymentAdjustmentTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, PaymentAdjustmentTransformerInterface::KEY_PAYMENT_ADJUSTMENT_ID));

        $transformer->transform($data);
    }
}
