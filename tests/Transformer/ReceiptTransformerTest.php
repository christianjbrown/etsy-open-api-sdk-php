<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Model\Receipt;
use ChristianBrown\Etsy\Model\ReceiptInterface;
use ChristianBrown\Etsy\Model\RefundInterface;
use ChristianBrown\Etsy\Model\ShipmentInterface;
use ChristianBrown\Etsy\Model\TransactionInterface;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformerInterface;
use ChristianBrown\Etsy\Transformer\RefundsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShipmentsTransformerInterface;
use ChristianBrown\Etsy\Transformer\TransactionsTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(Receipt::class)]
#[CoversClass(ReceiptTransformer::class)]
final class ReceiptTransformerTest extends TestCase
{
    public function testSetReceiptId(): void
    {
        $receipt = new Receipt(1);

        self::assertSame(2, $receipt->setReceiptId(2)->getReceiptId());
    }

    public function testTransform(): void
    {
        $grandtotalData = ['__grandtotal__'];
        $subtotalData = ['__subtotal__'];
        $totalPriceData = ['__total_price__'];
        $totalShippingCostData = ['__total_shipping_cost__'];
        $totalTaxCostData = ['__total_tax_cost__'];
        $totalVatCostData = ['__total_vat_cost__'];
        $discountAmtData = ['__discount_amt__'];
        $giftWrapPriceData = ['__gift_wrap_price__'];
        $shipmentsData = ['__shipments__'];
        $transactionsData = ['__transactions__'];
        $refundsData = ['__refunds__'];

        $data = [
            ReceiptTransformerInterface::KEY_RECEIPT_ID => 9000,
            ReceiptTransformerInterface::KEY_RECEIPT_TYPE => 1,
            ReceiptTransformerInterface::KEY_SELLER_USER_ID => 11,
            ReceiptTransformerInterface::KEY_SELLER_EMAIL => 'seller@example.com',
            ReceiptTransformerInterface::KEY_BUYER_USER_ID => 12,
            ReceiptTransformerInterface::KEY_BUYER_EMAIL => 'buyer@example.com',
            ReceiptTransformerInterface::KEY_NAME => 'Jane Buyer',
            ReceiptTransformerInterface::KEY_FIRST_LINE => '1 Main St',
            ReceiptTransformerInterface::KEY_SECOND_LINE => 'Apt 2',
            ReceiptTransformerInterface::KEY_CITY => 'Townsville',
            ReceiptTransformerInterface::KEY_STATE => 'CA',
            ReceiptTransformerInterface::KEY_ZIP => '90210',
            ReceiptTransformerInterface::KEY_STATUS => 'paid',
            ReceiptTransformerInterface::KEY_FORMATTED_ADDRESS => '1 Main St, Townsville',
            ReceiptTransformerInterface::KEY_COUNTRY_ISO => 'US',
            ReceiptTransformerInterface::KEY_PAYMENT_METHOD => 'cc',
            ReceiptTransformerInterface::KEY_PAYMENT_EMAIL => 'pay@example.com',
            ReceiptTransformerInterface::KEY_MESSAGE_FROM_SELLER => 'from seller',
            ReceiptTransformerInterface::KEY_MESSAGE_FROM_BUYER => 'from buyer',
            ReceiptTransformerInterface::KEY_MESSAGE_FROM_PAYMENT => 'from payment',
            ReceiptTransformerInterface::KEY_IS_PAID => true,
            ReceiptTransformerInterface::KEY_IS_SHIPPED => true,
            ReceiptTransformerInterface::KEY_CREATE_TIMESTAMP => 1600000001,
            ReceiptTransformerInterface::KEY_CREATED_TIMESTAMP => 1600000002,
            ReceiptTransformerInterface::KEY_UPDATE_TIMESTAMP => 1600000003,
            ReceiptTransformerInterface::KEY_UPDATED_TIMESTAMP => 1600000004,
            ReceiptTransformerInterface::KEY_IS_GIFT => true,
            ReceiptTransformerInterface::KEY_GIFT_MESSAGE => 'happy birthday',
            ReceiptTransformerInterface::KEY_GIFT_SENDER => 'Aunt May',
            ReceiptTransformerInterface::KEY_GRANDTOTAL => $grandtotalData,
            ReceiptTransformerInterface::KEY_SUBTOTAL => $subtotalData,
            ReceiptTransformerInterface::KEY_TOTAL_PRICE => $totalPriceData,
            ReceiptTransformerInterface::KEY_TOTAL_SHIPPING_COST => $totalShippingCostData,
            ReceiptTransformerInterface::KEY_TOTAL_TAX_COST => $totalTaxCostData,
            ReceiptTransformerInterface::KEY_TOTAL_VAT_COST => $totalVatCostData,
            ReceiptTransformerInterface::KEY_DISCOUNT_AMT => $discountAmtData,
            ReceiptTransformerInterface::KEY_GIFT_WRAP_PRICE => $giftWrapPriceData,
            ReceiptTransformerInterface::KEY_SHIPMENTS => $shipmentsData,
            ReceiptTransformerInterface::KEY_TRANSACTIONS => $transactionsData,
            ReceiptTransformerInterface::KEY_REFUNDS => $refundsData,
        ];

        $grandtotal = self::createStub(MoneyInterface::class);
        $subtotal = self::createStub(MoneyInterface::class);
        $totalPrice = self::createStub(MoneyInterface::class);
        $totalShippingCost = self::createStub(MoneyInterface::class);
        $totalTaxCost = self::createStub(MoneyInterface::class);
        $totalVatCost = self::createStub(MoneyInterface::class);
        $discountAmt = self::createStub(MoneyInterface::class);
        $giftWrapPrice = self::createStub(MoneyInterface::class);
        $shipment = self::createStub(ShipmentInterface::class);
        $transaction = self::createStub(TransactionInterface::class);
        $refund = self::createStub(RefundInterface::class);

        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);
        $moneyTransformer->method('transform')
            ->willReturnMap(
                [
                    [$grandtotalData, $grandtotal],
                    [$subtotalData, $subtotal],
                    [$totalPriceData, $totalPrice],
                    [$totalShippingCostData, $totalShippingCost],
                    [$totalTaxCostData, $totalTaxCost],
                    [$totalVatCostData, $totalVatCost],
                    [$discountAmtData, $discountAmt],
                    [$giftWrapPriceData, $giftWrapPrice],
                ]
            );

        $transactionsTransformer = self::createMock(TransactionsTransformerInterface::class);
        $transactionsTransformer->expects(self::once())->method('transform')
            ->with($transactionsData)
            ->willReturn([$transaction]);

        $refundsTransformer = self::createMock(RefundsTransformerInterface::class);
        $refundsTransformer->expects(self::once())->method('transform')
            ->with($refundsData)
            ->willReturn([$refund]);

        $shipmentsTransformer = self::createMock(ShipmentsTransformerInterface::class);
        $shipmentsTransformer->expects(self::once())->method('transform')
            ->with($shipmentsData)
            ->willReturn([$shipment]);

        $transformer = new ReceiptTransformer($moneyTransformer, $transactionsTransformer, $refundsTransformer, $shipmentsTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getReceiptId());
        self::assertSame(1, $actual->getReceiptType());
        self::assertSame(11, $actual->getSellerUserId());
        self::assertSame('seller@example.com', $actual->getSellerEmail());
        self::assertSame(12, $actual->getBuyerUserId());
        self::assertSame('buyer@example.com', $actual->getBuyerEmail());
        self::assertSame('Jane Buyer', $actual->getName());
        self::assertSame('1 Main St', $actual->getFirstLine());
        self::assertSame('Apt 2', $actual->getSecondLine());
        self::assertSame('Townsville', $actual->getCity());
        self::assertSame('CA', $actual->getState());
        self::assertSame('90210', $actual->getZip());
        self::assertSame('paid', $actual->getStatus());
        self::assertSame('1 Main St, Townsville', $actual->getFormattedAddress());
        self::assertSame('US', $actual->getCountryIso());
        self::assertSame('cc', $actual->getPaymentMethod());
        self::assertSame('pay@example.com', $actual->getPaymentEmail());
        self::assertSame('from seller', $actual->getMessageFromSeller());
        self::assertSame('from buyer', $actual->getMessageFromBuyer());
        self::assertSame('from payment', $actual->getMessageFromPayment());
        self::assertTrue($actual->getIsPaid());
        self::assertTrue($actual->getIsShipped());
        self::assertSame(1600000001, $actual->getCreateTimestamp());
        self::assertSame(1600000002, $actual->getCreatedTimestamp());
        self::assertSame(1600000003, $actual->getUpdateTimestamp());
        self::assertSame(1600000004, $actual->getUpdatedTimestamp());
        self::assertTrue($actual->getIsGift());
        self::assertSame('happy birthday', $actual->getGiftMessage());
        self::assertSame('Aunt May', $actual->getGiftSender());
        self::assertSame($grandtotal, $actual->getGrandtotal());
        self::assertSame($subtotal, $actual->getSubtotal());
        self::assertSame($totalPrice, $actual->getTotalPrice());
        self::assertSame($totalShippingCost, $actual->getTotalShippingCost());
        self::assertSame($totalTaxCost, $actual->getTotalTaxCost());
        self::assertSame($totalVatCost, $actual->getTotalVatCost());
        self::assertSame($discountAmt, $actual->getDiscountAmt());
        self::assertSame($giftWrapPrice, $actual->getGiftWrapPrice());
        self::assertSame([$shipment], $actual->getShipments());
        self::assertSame([$transaction], $actual->getTransactions());
        self::assertSame([$refund], $actual->getRefunds());
    }

    /**
     * Each case supplies the required receipt_id plus at most one optional field
     * in an absent / wrong-type / falsy-but-valid state, isolating the
     * early-return and set paths of that field's apply helper.
     *
     * @param array<string, mixed>            $data
     * @param Closure(ReceiptInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ReceiptTransformer(
            self::createStub(MoneyTransformerInterface::class),
            self::createStub(TransactionsTransformerInterface::class),
            self::createStub(RefundsTransformerInterface::class),
            self::createStub(ShipmentsTransformerInterface::class),
        );

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ReceiptInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ReceiptTransformerInterface::KEY_RECEIPT_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ReceiptInterface $receipt): void {
                self::assertNull($receipt->getName());
                self::assertNull($receipt->getReceiptType());
                self::assertNull($receipt->getIsPaid());
                self::assertNull($receipt->getGrandtotal());
                self::assertSame([], $receipt->getShipments());
                self::assertSame([], $receipt->getTransactions());
                self::assertSame([], $receipt->getRefunds());
            },
        ];

        yield 'receiptTypeWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_RECEIPT_TYPE => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getReceiptType());
        }];
        yield 'receiptTypeZero' => [[$id => 1, ReceiptTransformerInterface::KEY_RECEIPT_TYPE => 0], static function (ReceiptInterface $r): void {
            self::assertSame(0, $r->getReceiptType());
        }];
        yield 'sellerUserIdWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_SELLER_USER_ID => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getSellerUserId());
        }];
        yield 'buyerUserIdWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_BUYER_USER_ID => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getBuyerUserId());
        }];
        yield 'createTimestampWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_CREATE_TIMESTAMP => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getCreateTimestamp());
        }];
        yield 'createdTimestampWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getCreatedTimestamp());
        }];
        yield 'updateTimestampWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_UPDATE_TIMESTAMP => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getUpdateTimestamp());
        }];
        yield 'updatedTimestampWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_UPDATED_TIMESTAMP => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getUpdatedTimestamp());
        }];
        yield 'sellerEmailWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_SELLER_EMAIL => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getSellerEmail());
        }];
        yield 'buyerEmailWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_BUYER_EMAIL => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getBuyerEmail());
        }];
        yield 'nameWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_NAME => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getName());
        }];
        yield 'firstLineWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_FIRST_LINE => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getFirstLine());
        }];
        yield 'secondLineWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_SECOND_LINE => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getSecondLine());
        }];
        yield 'cityWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_CITY => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getCity());
        }];
        yield 'stateWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_STATE => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getState());
        }];
        yield 'zipWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_ZIP => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getZip());
        }];
        yield 'statusWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_STATUS => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getStatus());
        }];
        yield 'formattedAddressWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_FORMATTED_ADDRESS => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getFormattedAddress());
        }];
        yield 'countryIsoWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_COUNTRY_ISO => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getCountryIso());
        }];
        yield 'paymentMethodWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_PAYMENT_METHOD => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getPaymentMethod());
        }];
        yield 'paymentEmailWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_PAYMENT_EMAIL => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getPaymentEmail());
        }];
        yield 'messageFromSellerWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_MESSAGE_FROM_SELLER => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getMessageFromSeller());
        }];
        yield 'messageFromBuyerWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_MESSAGE_FROM_BUYER => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getMessageFromBuyer());
        }];
        yield 'messageFromPaymentWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_MESSAGE_FROM_PAYMENT => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getMessageFromPayment());
        }];
        yield 'giftMessageWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_GIFT_MESSAGE => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getGiftMessage());
        }];
        yield 'giftSenderWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_GIFT_SENDER => 42], static function (ReceiptInterface $r): void {
            self::assertNull($r->getGiftSender());
        }];
        yield 'isPaidWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_IS_PAID => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getIsPaid());
        }];
        yield 'isPaidFalse' => [[$id => 1, ReceiptTransformerInterface::KEY_IS_PAID => false], static function (ReceiptInterface $r): void {
            self::assertFalse($r->getIsPaid());
        }];
        yield 'isShippedWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_IS_SHIPPED => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getIsShipped());
        }];
        yield 'isShippedFalse' => [[$id => 1, ReceiptTransformerInterface::KEY_IS_SHIPPED => false], static function (ReceiptInterface $r): void {
            self::assertFalse($r->getIsShipped());
        }];
        yield 'isGiftWrongType' => [[$id => 1, ReceiptTransformerInterface::KEY_IS_GIFT => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getIsGift());
        }];
        yield 'isGiftFalse' => [[$id => 1, ReceiptTransformerInterface::KEY_IS_GIFT => false], static function (ReceiptInterface $r): void {
            self::assertFalse($r->getIsGift());
        }];
        yield 'grandtotalNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_GRANDTOTAL => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getGrandtotal());
        }];
        yield 'subtotalNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_SUBTOTAL => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getSubtotal());
        }];
        yield 'totalPriceNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_TOTAL_PRICE => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getTotalPrice());
        }];
        yield 'totalShippingCostNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_TOTAL_SHIPPING_COST => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getTotalShippingCost());
        }];
        yield 'totalTaxCostNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_TOTAL_TAX_COST => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getTotalTaxCost());
        }];
        yield 'totalVatCostNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_TOTAL_VAT_COST => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getTotalVatCost());
        }];
        yield 'discountAmtNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_DISCOUNT_AMT => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getDiscountAmt());
        }];
        yield 'giftWrapPriceNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_GIFT_WRAP_PRICE => 'x'], static function (ReceiptInterface $r): void {
            self::assertNull($r->getGiftWrapPrice());
        }];
        yield 'shipmentsNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_SHIPMENTS => 'x'], static function (ReceiptInterface $r): void {
            self::assertSame([], $r->getShipments());
        }];
        yield 'transactionsNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_TRANSACTIONS => 'x'], static function (ReceiptInterface $r): void {
            self::assertSame([], $r->getTransactions());
        }];
        yield 'refundsNonArray' => [[$id => 1, ReceiptTransformerInterface::KEY_REFUNDS => 'x'], static function (ReceiptInterface $r): void {
            self::assertSame([], $r->getRefunds());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ReceiptTransformerInterface::KEY_RECEIPT_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidReceiptId(array $data): void
    {
        $transformer = new ReceiptTransformer(
            self::createStub(MoneyTransformerInterface::class),
            self::createStub(TransactionsTransformerInterface::class),
            self::createStub(RefundsTransformerInterface::class),
            self::createStub(ShipmentsTransformerInterface::class),
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReceiptTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ReceiptTransformerInterface::KEY_RECEIPT_ID));

        $transformer->transform($data);
    }
}
