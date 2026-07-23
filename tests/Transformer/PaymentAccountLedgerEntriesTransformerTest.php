<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAccountLedgerEntryInterface;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntriesTransformer;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntriesTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentAccountLedgerEntryTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(PaymentAccountLedgerEntriesTransformer::class)]
final class PaymentAccountLedgerEntriesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-entry-1'], ['test-entry-2']];

        $entry1 = self::createStub(PaymentAccountLedgerEntryInterface::class);
        $entry2 = self::createStub(PaymentAccountLedgerEntryInterface::class);

        $entryTransformer = self::createStub(PaymentAccountLedgerEntryTransformerInterface::class);
        $entryTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-entry-1'], $entry1],
                    [['test-entry-2'], $entry2],
                ]
            );

        $transformer = new PaymentAccountLedgerEntriesTransformer($entryTransformer);

        self::assertSame([$entry1, $entry2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $entryTransformer = self::createStub(PaymentAccountLedgerEntryTransformerInterface::class);

        $transformer = new PaymentAccountLedgerEntriesTransformer($entryTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $entryTransformer = self::createStub(PaymentAccountLedgerEntryTransformerInterface::class);

        $transformer = new PaymentAccountLedgerEntriesTransformer($entryTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PaymentAccountLedgerEntriesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, PaymentAccountLedgerEntriesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
