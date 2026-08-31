<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ReceiptInterface;
use ChristianBrown\Etsy\Model\ReceiptPage;
use ChristianBrown\Etsy\Transformer\ReceiptPageTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptPageTransformerInterface;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ReceiptPage::class)]
#[CoversClass(ReceiptPageTransformer::class)]
final class ReceiptPageTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $resultsData = [['test-receipt-1'], ['test-receipt-2']];
        $receipts = [self::createStub(ReceiptInterface::class), self::createStub(ReceiptInterface::class)];

        $receiptsTransformer = self::createMock(ReceiptsTransformerInterface::class);
        $receiptsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($receipts);

        $transformer = new ReceiptPageTransformer($receiptsTransformer);

        $page = $transformer->transform(
            [
                ReceiptPageTransformerInterface::KEY_COUNT => 137,
                ReceiptPageTransformerInterface::KEY_RESULTS => $resultsData,
            ]
        );

        self::assertSame(137, $page->getCount());
        self::assertSame($receipts, $page->getReceipts());
        self::assertSame(9, $page->setCount(9)->getCount());
        self::assertSame([], $page->setReceipts([])->getReceipts());
    }

    public function testTransformThrowsWhenCountMissing(): void
    {
        $transformer = new ReceiptPageTransformer(self::createStub(ReceiptsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReceiptPageTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ReceiptPageTransformerInterface::KEY_COUNT));

        $transformer->transform([ReceiptPageTransformerInterface::KEY_RESULTS => []]);
    }

    public function testTransformThrowsWhenCountNotInteger(): void
    {
        $transformer = new ReceiptPageTransformer(self::createStub(ReceiptsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReceiptPageTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ReceiptPageTransformerInterface::KEY_COUNT));

        $transformer->transform([ReceiptPageTransformerInterface::KEY_COUNT => 'not-an-integer']);
    }

    public function testTransformWithEmptyResults(): void
    {
        $receiptsTransformer = self::createMock(ReceiptsTransformerInterface::class);
        $receiptsTransformer->expects(self::never())->method('transform');

        $transformer = new ReceiptPageTransformer($receiptsTransformer);

        $page = $transformer->transform(
            [
                ReceiptPageTransformerInterface::KEY_COUNT => 0,
                ReceiptPageTransformerInterface::KEY_RESULTS => [],
            ]
        );

        self::assertSame(0, $page->getCount());
        self::assertSame([], $page->getReceipts());
    }

    public function testTransformWithResultsNotArray(): void
    {
        $receiptsTransformer = self::createMock(ReceiptsTransformerInterface::class);
        $receiptsTransformer->expects(self::never())->method('transform');

        $transformer = new ReceiptPageTransformer($receiptsTransformer);

        $page = $transformer->transform(
            [
                ReceiptPageTransformerInterface::KEY_COUNT => 4,
                ReceiptPageTransformerInterface::KEY_RESULTS => 'not-an-array',
            ]
        );

        self::assertSame(4, $page->getCount());
        self::assertSame([], $page->getReceipts());
    }
}
