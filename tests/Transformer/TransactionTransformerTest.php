<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\Transaction;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformerInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(Transaction::class)]
#[CoversClass(TransactionTransformer::class)]
final class TransactionTransformerTest extends TestCase
{
    public function test(): void
    {
        $data = [
            TransactionTransformerInterface::DATA_KEY_LISTING_ID => 123,
            TransactionTransformerInterface::DATA_KEY_QUANTITY => 42,
        ];

        $transformer = new TransactionTransformer();
        $actual = $transformer->transform($data);

        self::assertSame(123, $actual->getListingId());
        self::assertSame(42, $actual->getQuantity());
    }

    #[TestWith(
        [
            [
                TransactionTransformerInterface::DATA_KEY_QUANTITY => 42,
            ],
            TransactionTransformerInterface::DATA_KEY_LISTING_ID,
        ]
    )]
    #[TestWith(
        [
            [
                TransactionTransformerInterface::DATA_KEY_LISTING_ID => 123,
            ],
            TransactionTransformerInterface::DATA_KEY_QUANTITY,
        ]
    )]
    public function testFieldMissing(array $data, string $missingKey): void
    {
        $transformer = new TransactionTransformer();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s is not set.', $missingKey));
        $transformer->transform($data);
    }

    #[TestWith(
        [
            [
                TransactionTransformerInterface::DATA_KEY_LISTING_ID => 'test-invalid-type',
                TransactionTransformerInterface::DATA_KEY_QUANTITY => 42,
            ],
            TransactionTransformerInterface::DATA_KEY_LISTING_ID,
        ]
    )]
    #[TestWith(
        [
            [
                TransactionTransformerInterface::DATA_KEY_LISTING_ID => 123,
                TransactionTransformerInterface::DATA_KEY_QUANTITY => 'test-invalid-type',
            ],
            TransactionTransformerInterface::DATA_KEY_QUANTITY,
        ]
    )]
    public function testFieldWrongType(array $data, string $missingKey): void
    {
        $transformer = new TransactionTransformer();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s is not numeric.', $missingKey));
        $transformer->transform($data);
    }
}
