<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Model\Payment;
use ChristianBrown\Etsy\Model\PaymentAdjustmentInterface;
use ChristianBrown\Etsy\Model\PaymentInterface;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentsTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentTransformer;
use ChristianBrown\Etsy\Transformer\PaymentTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(Payment::class)]
#[CoversClass(PaymentTransformer::class)]
final class PaymentTransformerTest extends TestCase
{
    public function testSetPaymentId(): void
    {
        $payment = new Payment(1);

        self::assertSame(2, $payment->setPaymentId(2)->getPaymentId());
    }

    public function testTransform(): void
    {
        $amountGrossData = ['__amount_gross__'];
        $amountFeesData = ['__amount_fees__'];
        $amountNetData = ['__amount_net__'];
        $postedGrossData = ['__posted_gross__'];
        $postedFeesData = ['__posted_fees__'];
        $postedNetData = ['__posted_net__'];
        $adjustedGrossData = ['__adjusted_gross__'];
        $adjustedFeesData = ['__adjusted_fees__'];
        $adjustedNetData = ['__adjusted_net__'];
        $paymentAdjustmentsData = ['__payment_adjustments__'];

        $data = [
            PaymentTransformerInterface::KEY_PAYMENT_ID => 1000,
            PaymentTransformerInterface::KEY_BUYER_USER_ID => 11,
            PaymentTransformerInterface::KEY_SHOP_ID => 12,
            PaymentTransformerInterface::KEY_RECEIPT_ID => 13,
            PaymentTransformerInterface::KEY_AMOUNT_GROSS => $amountGrossData,
            PaymentTransformerInterface::KEY_AMOUNT_FEES => $amountFeesData,
            PaymentTransformerInterface::KEY_AMOUNT_NET => $amountNetData,
            PaymentTransformerInterface::KEY_POSTED_GROSS => $postedGrossData,
            PaymentTransformerInterface::KEY_POSTED_FEES => $postedFeesData,
            PaymentTransformerInterface::KEY_POSTED_NET => $postedNetData,
            PaymentTransformerInterface::KEY_ADJUSTED_GROSS => $adjustedGrossData,
            PaymentTransformerInterface::KEY_ADJUSTED_FEES => $adjustedFeesData,
            PaymentTransformerInterface::KEY_ADJUSTED_NET => $adjustedNetData,
            PaymentTransformerInterface::KEY_CURRENCY => 'USD',
            PaymentTransformerInterface::KEY_SHOP_CURRENCY => 'GBP',
            PaymentTransformerInterface::KEY_BUYER_CURRENCY => 'EUR',
            PaymentTransformerInterface::KEY_SHIPPING_USER_ID => 14,
            PaymentTransformerInterface::KEY_SHIPPING_ADDRESS_ID => 15,
            PaymentTransformerInterface::KEY_BILLING_ADDRESS_ID => 16,
            PaymentTransformerInterface::KEY_STATUS => 'settled',
            PaymentTransformerInterface::KEY_SHIPPED_TIMESTAMP => 1600000001,
            PaymentTransformerInterface::KEY_CREATE_TIMESTAMP => 1600000002,
            PaymentTransformerInterface::KEY_CREATED_TIMESTAMP => 1600000003,
            PaymentTransformerInterface::KEY_UPDATE_TIMESTAMP => 1600000004,
            PaymentTransformerInterface::KEY_UPDATED_TIMESTAMP => 1600000005,
            PaymentTransformerInterface::KEY_PAYMENT_ADJUSTMENTS => $paymentAdjustmentsData,
        ];

        $amountGross = self::createStub(MoneyInterface::class);
        $amountFees = self::createStub(MoneyInterface::class);
        $amountNet = self::createStub(MoneyInterface::class);
        $postedGross = self::createStub(MoneyInterface::class);
        $postedFees = self::createStub(MoneyInterface::class);
        $postedNet = self::createStub(MoneyInterface::class);
        $adjustedGross = self::createStub(MoneyInterface::class);
        $adjustedFees = self::createStub(MoneyInterface::class);
        $adjustedNet = self::createStub(MoneyInterface::class);

        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);
        $moneyTransformer->method('transform')
            ->willReturnMap(
                [
                    [$amountGrossData, $amountGross],
                    [$amountFeesData, $amountFees],
                    [$amountNetData, $amountNet],
                    [$postedGrossData, $postedGross],
                    [$postedFeesData, $postedFees],
                    [$postedNetData, $postedNet],
                    [$adjustedGrossData, $adjustedGross],
                    [$adjustedFeesData, $adjustedFees],
                    [$adjustedNetData, $adjustedNet],
                ]
            );

        $adjustment = self::createStub(PaymentAdjustmentInterface::class);
        $adjustmentsTransformer = self::createMock(PaymentAdjustmentsTransformerInterface::class);
        $adjustmentsTransformer->expects(self::once())->method('transform')
            ->with($paymentAdjustmentsData)
            ->willReturn([$adjustment]);

        $transformer = new PaymentTransformer($moneyTransformer, $adjustmentsTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(1000, $actual->getPaymentId());
        self::assertSame(11, $actual->getBuyerUserId());
        self::assertSame(12, $actual->getShopId());
        self::assertSame(13, $actual->getReceiptId());
        self::assertSame($amountGross, $actual->getAmountGross());
        self::assertSame($amountFees, $actual->getAmountFees());
        self::assertSame($amountNet, $actual->getAmountNet());
        self::assertSame($postedGross, $actual->getPostedGross());
        self::assertSame($postedFees, $actual->getPostedFees());
        self::assertSame($postedNet, $actual->getPostedNet());
        self::assertSame($adjustedGross, $actual->getAdjustedGross());
        self::assertSame($adjustedFees, $actual->getAdjustedFees());
        self::assertSame($adjustedNet, $actual->getAdjustedNet());
        self::assertSame('USD', $actual->getCurrency());
        self::assertSame('GBP', $actual->getShopCurrency());
        self::assertSame('EUR', $actual->getBuyerCurrency());
        self::assertSame(14, $actual->getShippingUserId());
        self::assertSame(15, $actual->getShippingAddressId());
        self::assertSame(16, $actual->getBillingAddressId());
        self::assertSame('settled', $actual->getStatus());
        self::assertSame(1600000001, $actual->getShippedTimestamp());
        self::assertSame(1600000002, $actual->getCreateTimestamp());
        self::assertSame(1600000003, $actual->getCreatedTimestamp());
        self::assertSame(1600000004, $actual->getUpdateTimestamp());
        self::assertSame(1600000005, $actual->getUpdatedTimestamp());
        self::assertSame([$adjustment], $actual->getPaymentAdjustments());
    }

    /**
     * Each case supplies the required payment_id plus at most one optional
     * field in an absent / wrong-type / falsy-but-valid state, isolating the
     * early-return and set paths of that field's apply helper.
     *
     * @param array<string, mixed>            $data
     * @param Closure(PaymentInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new PaymentTransformer(
            self::createStub(MoneyTransformerInterface::class),
            self::createStub(PaymentAdjustmentsTransformerInterface::class),
        );

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(PaymentInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = PaymentTransformerInterface::KEY_PAYMENT_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (PaymentInterface $payment): void {
                self::assertNull($payment->getBuyerUserId());
                self::assertNull($payment->getShopId());
                self::assertNull($payment->getCurrency());
                self::assertNull($payment->getAmountGross());
                self::assertNull($payment->getPostedFees());
                self::assertNull($payment->getAdjustedNet());
                self::assertSame([], $payment->getPaymentAdjustments());
            },
        ];

        yield 'billingAddressIdWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_BILLING_ADDRESS_ID => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getBillingAddressId());
        }];
        yield 'billingAddressIdZero' => [[$id => 1, PaymentTransformerInterface::KEY_BILLING_ADDRESS_ID => 0], static function (PaymentInterface $p): void {
            self::assertSame(0, $p->getBillingAddressId());
        }];
        yield 'buyerCurrencyWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_BUYER_CURRENCY => 42], static function (PaymentInterface $p): void {
            self::assertNull($p->getBuyerCurrency());
        }];
        yield 'buyerUserIdWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_BUYER_USER_ID => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getBuyerUserId());
        }];
        yield 'createdTimestampWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getCreatedTimestamp());
        }];
        yield 'createTimestampWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_CREATE_TIMESTAMP => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getCreateTimestamp());
        }];
        yield 'currencyWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_CURRENCY => 42], static function (PaymentInterface $p): void {
            self::assertNull($p->getCurrency());
        }];
        yield 'receiptIdWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_RECEIPT_ID => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getReceiptId());
        }];
        yield 'shippedTimestampWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_SHIPPED_TIMESTAMP => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getShippedTimestamp());
        }];
        yield 'shippingAddressIdWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_SHIPPING_ADDRESS_ID => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getShippingAddressId());
        }];
        yield 'shippingUserIdWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_SHIPPING_USER_ID => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getShippingUserId());
        }];
        yield 'shopCurrencyWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_SHOP_CURRENCY => 42], static function (PaymentInterface $p): void {
            self::assertNull($p->getShopCurrency());
        }];
        yield 'shopIdWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_SHOP_ID => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getShopId());
        }];
        yield 'statusWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_STATUS => 42], static function (PaymentInterface $p): void {
            self::assertNull($p->getStatus());
        }];
        yield 'updatedTimestampWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_UPDATED_TIMESTAMP => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getUpdatedTimestamp());
        }];
        yield 'updateTimestampWrongType' => [[$id => 1, PaymentTransformerInterface::KEY_UPDATE_TIMESTAMP => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getUpdateTimestamp());
        }];
        yield 'adjustedFeesNonArray' => [[$id => 1, PaymentTransformerInterface::KEY_ADJUSTED_FEES => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getAdjustedFees());
        }];
        yield 'adjustedGrossNonArray' => [[$id => 1, PaymentTransformerInterface::KEY_ADJUSTED_GROSS => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getAdjustedGross());
        }];
        yield 'adjustedNetNonArray' => [[$id => 1, PaymentTransformerInterface::KEY_ADJUSTED_NET => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getAdjustedNet());
        }];
        yield 'amountFeesNonArray' => [[$id => 1, PaymentTransformerInterface::KEY_AMOUNT_FEES => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getAmountFees());
        }];
        yield 'amountGrossNonArray' => [[$id => 1, PaymentTransformerInterface::KEY_AMOUNT_GROSS => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getAmountGross());
        }];
        yield 'amountNetNonArray' => [[$id => 1, PaymentTransformerInterface::KEY_AMOUNT_NET => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getAmountNet());
        }];
        yield 'postedFeesNonArray' => [[$id => 1, PaymentTransformerInterface::KEY_POSTED_FEES => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getPostedFees());
        }];
        yield 'postedGrossNonArray' => [[$id => 1, PaymentTransformerInterface::KEY_POSTED_GROSS => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getPostedGross());
        }];
        yield 'postedNetNonArray' => [[$id => 1, PaymentTransformerInterface::KEY_POSTED_NET => 'x'], static function (PaymentInterface $p): void {
            self::assertNull($p->getPostedNet());
        }];
        yield 'paymentAdjustmentsNonArray' => [[$id => 1, PaymentTransformerInterface::KEY_PAYMENT_ADJUSTMENTS => 'x'], static function (PaymentInterface $p): void {
            self::assertSame([], $p->getPaymentAdjustments());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[PaymentTransformerInterface::KEY_PAYMENT_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidPaymentId(array $data): void
    {
        $transformer = new PaymentTransformer(
            self::createStub(MoneyTransformerInterface::class),
            self::createStub(PaymentAdjustmentsTransformerInterface::class),
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PaymentTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, PaymentTransformerInterface::KEY_PAYMENT_ID));

        $transformer->transform($data);
    }
}
