<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntry;
use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntryInterface;
use ChristianBrown\Etsy\Model\PaymentAdjustmentInterface;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntryTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntryTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentAdjustmentsTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(PaymentAccountLedgerEntry::class)]
#[CoversClass(PaymentAccountLedgerEntryTransformer::class)]
final class PaymentAccountLedgerEntryTransformerTest extends TestCase
{
    public function testSetEntryId(): void
    {
        $entry = new PaymentAccountLedgerEntry(1);

        self::assertSame(2, $entry->setEntryId(2)->getEntryId());
    }

    public function testTransform(): void
    {
        $paymentAdjustmentsData = ['__payment_adjustments__'];

        $data = [
            PaymentAccountLedgerEntryTransformerInterface::KEY_ENTRY_ID => 1000,
            PaymentAccountLedgerEntryTransformerInterface::KEY_LEDGER_ID => 11,
            PaymentAccountLedgerEntryTransformerInterface::KEY_SEQUENCE_NUMBER => 12,
            PaymentAccountLedgerEntryTransformerInterface::KEY_AMOUNT => -13,
            PaymentAccountLedgerEntryTransformerInterface::KEY_CURRENCY => 'USD',
            PaymentAccountLedgerEntryTransformerInterface::KEY_DESCRIPTION => 'A payment',
            PaymentAccountLedgerEntryTransformerInterface::KEY_BALANCE => 14,
            PaymentAccountLedgerEntryTransformerInterface::KEY_CREATE_DATE => 1600000001,
            PaymentAccountLedgerEntryTransformerInterface::KEY_CREATED_TIMESTAMP => 1600000002,
            PaymentAccountLedgerEntryTransformerInterface::KEY_LEDGER_TYPE => 'Payment',
            PaymentAccountLedgerEntryTransformerInterface::KEY_REFERENCE_TYPE => 'receipt',
            PaymentAccountLedgerEntryTransformerInterface::KEY_REFERENCE_ID => '99',
            PaymentAccountLedgerEntryTransformerInterface::KEY_PARENT_ENTRY_ID => 15,
            PaymentAccountLedgerEntryTransformerInterface::KEY_PAYMENT_ADJUSTMENTS => $paymentAdjustmentsData,
        ];

        $adjustment = self::createStub(PaymentAdjustmentInterface::class);
        $adjustmentsTransformer = self::createMock(PaymentAdjustmentsTransformerInterface::class);
        $adjustmentsTransformer->expects(self::once())->method('transform')
            ->with($paymentAdjustmentsData)
            ->willReturn([$adjustment]);

        $transformer = new PaymentAccountLedgerEntryTransformer($adjustmentsTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(1000, $actual->getEntryId());
        self::assertSame(11, $actual->getLedgerId());
        self::assertSame(12, $actual->getSequenceNumber());
        self::assertSame(-13, $actual->getAmount());
        self::assertSame('USD', $actual->getCurrency());
        self::assertSame('A payment', $actual->getDescription());
        self::assertSame(14, $actual->getBalance());
        self::assertSame(1600000001, $actual->getCreateDate());
        self::assertSame(1600000002, $actual->getCreatedTimestamp());
        self::assertSame('Payment', $actual->getLedgerType());
        self::assertSame('receipt', $actual->getReferenceType());
        self::assertSame('99', $actual->getReferenceId());
        self::assertSame(15, $actual->getParentEntryId());
        self::assertSame([$adjustment], $actual->getPaymentAdjustments());
    }

    /**
     * Each case supplies the required entry_id plus at most one optional field
     * in an absent / wrong-type / falsy-but-valid state, isolating the
     * early-return and set paths of that field's apply helper.
     *
     * @param array<string, mixed>                              $data
     * @param Closure(PaymentAccountLedgerEntryInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new PaymentAccountLedgerEntryTransformer(self::createStub(PaymentAdjustmentsTransformerInterface::class));

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(PaymentAccountLedgerEntryInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = PaymentAccountLedgerEntryTransformerInterface::KEY_ENTRY_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (PaymentAccountLedgerEntryInterface $entry): void {
                self::assertNull($entry->getLedgerId());
                self::assertNull($entry->getAmount());
                self::assertNull($entry->getBalance());
                self::assertNull($entry->getCurrency());
                self::assertNull($entry->getDescription());
                self::assertNull($entry->getReferenceId());
                self::assertSame([], $entry->getPaymentAdjustments());
            },
        ];

        yield 'amountWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_AMOUNT => 'x'], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getAmount());
        }];
        yield 'amountZero' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_AMOUNT => 0], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertSame(0, $e->getAmount());
        }];
        yield 'balanceWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_BALANCE => 'x'], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getBalance());
        }];
        yield 'createDateWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_CREATE_DATE => 'x'], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getCreateDate());
        }];
        yield 'createdTimestampWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getCreatedTimestamp());
        }];
        yield 'currencyWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_CURRENCY => 42], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getCurrency());
        }];
        yield 'descriptionWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_DESCRIPTION => 42], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getDescription());
        }];
        yield 'ledgerIdWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_LEDGER_ID => 'x'], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getLedgerId());
        }];
        yield 'ledgerTypeWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_LEDGER_TYPE => 42], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getLedgerType());
        }];
        yield 'parentEntryIdWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_PARENT_ENTRY_ID => 'x'], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getParentEntryId());
        }];
        yield 'parentEntryIdZero' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_PARENT_ENTRY_ID => 0], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertSame(0, $e->getParentEntryId());
        }];
        yield 'referenceIdWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_REFERENCE_ID => 42], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getReferenceId());
        }];
        yield 'referenceTypeWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_REFERENCE_TYPE => 42], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getReferenceType());
        }];
        yield 'sequenceNumberWrongType' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_SEQUENCE_NUMBER => 'x'], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertNull($e->getSequenceNumber());
        }];
        yield 'paymentAdjustmentsNonArray' => [[$id => 1, PaymentAccountLedgerEntryTransformerInterface::KEY_PAYMENT_ADJUSTMENTS => 'x'], static function (PaymentAccountLedgerEntryInterface $e): void {
            self::assertSame([], $e->getPaymentAdjustments());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[PaymentAccountLedgerEntryTransformerInterface::KEY_ENTRY_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidEntryId(array $data): void
    {
        $transformer = new PaymentAccountLedgerEntryTransformer(self::createStub(PaymentAdjustmentsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PaymentAccountLedgerEntryTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, PaymentAccountLedgerEntryTransformerInterface::KEY_ENTRY_ID));

        $transformer->transform($data);
    }
}
