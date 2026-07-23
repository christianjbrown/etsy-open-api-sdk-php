<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Model\Refund;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use ChristianBrown\Etsy\Transformer\RefundTransformer;
use ChristianBrown\Etsy\Transformer\RefundTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Refund::class)]
#[CoversClass(RefundTransformer::class)]
final class RefundTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $amountData = ['__amount__'];
        $data = [
            RefundTransformerInterface::KEY_AMOUNT => $amountData,
            RefundTransformerInterface::KEY_CREATED_TIMESTAMP => 1600000000,
            RefundTransformerInterface::KEY_NOTE_FROM_ISSUER => 'note',
            RefundTransformerInterface::KEY_REASON => 'reason',
            RefundTransformerInterface::KEY_STATUS => 'complete',
        ];

        $money = self::createStub(MoneyInterface::class);

        $moneyTransformer = self::createMock(MoneyTransformerInterface::class);
        $moneyTransformer->expects(self::once())->method('transform')
            ->with($amountData)
            ->willReturn($money);

        $transformer = new RefundTransformer($moneyTransformer);

        $actual = $transformer->transform($data);

        self::assertSame($money, $actual->getAmount());
        self::assertSame(1600000000, $actual->getCreatedTimestamp());
        self::assertSame('note', $actual->getNoteFromIssuer());
        self::assertSame('reason', $actual->getReason());
        self::assertSame('complete', $actual->getStatus());
    }

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('provideTransformAmountNotSetCases')]
    public function testTransformAmountNotSet(array $data): void
    {
        $moneyTransformer = self::createMock(MoneyTransformerInterface::class);
        $moneyTransformer->expects(self::never())->method('transform');

        $transformer = new RefundTransformer($moneyTransformer);

        self::assertNull($transformer->transform($data)->getAmount());
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function provideTransformAmountNotSetCases(): iterable
    {
        yield 'absent' => [[]];
        yield 'nonArray' => [[RefundTransformerInterface::KEY_AMOUNT => 'not-an-array']];
    }

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('provideTransformScalarFieldStatesCases')]
    public function testTransformScalarFieldStates(array $data, ?int $expectedCreatedTimestamp, ?string $expectedNoteFromIssuer, ?string $expectedReason, ?string $expectedStatus): void
    {
        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);

        $transformer = new RefundTransformer($moneyTransformer);

        $actual = $transformer->transform($data);

        self::assertSame($expectedCreatedTimestamp, $actual->getCreatedTimestamp());
        self::assertSame($expectedNoteFromIssuer, $actual->getNoteFromIssuer());
        self::assertSame($expectedReason, $actual->getReason());
        self::assertSame($expectedStatus, $actual->getStatus());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, ?int, ?string, ?string, ?string}>
     */
    public static function provideTransformScalarFieldStatesCases(): iterable
    {
        $createdTimestamp = RefundTransformerInterface::KEY_CREATED_TIMESTAMP;
        $noteFromIssuer = RefundTransformerInterface::KEY_NOTE_FROM_ISSUER;
        $reason = RefundTransformerInterface::KEY_REASON;
        $status = RefundTransformerInterface::KEY_STATUS;

        yield 'allAbsent' => [[], null, null, null, null];
        yield 'createdTimestampZero' => [[$createdTimestamp => 0], 0, null, null, null];
        yield 'createdTimestampWrongType' => [[$createdTimestamp => 'not-int'], null, null, null, null];
        yield 'noteFromIssuerWrongType' => [[$noteFromIssuer => 42], null, null, null, null];
        yield 'reasonWrongType' => [[$reason => 42], null, null, null, null];
        yield 'statusWrongType' => [[$status => 42], null, null, null, null];
    }
}
