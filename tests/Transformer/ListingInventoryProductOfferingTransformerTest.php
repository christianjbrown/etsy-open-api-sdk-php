<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryProductOffering;
use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;
use ChristianBrown\Etsy\Model\MoneyInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingTransformerInterface;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingInventoryProductOffering::class)]
#[CoversClass(ListingInventoryProductOfferingTransformer::class)]
final class ListingInventoryProductOfferingTransformerTest extends TestCase
{
    public function testSetOfferingId(): void
    {
        $offering = new ListingInventoryProductOffering(1);

        self::assertSame(2, $offering->setOfferingId(2)->getOfferingId());
    }

    public function testTransform(): void
    {
        $priceData = ['__price__'];
        $price = self::createStub(MoneyInterface::class);

        $data = [
            ListingInventoryProductOfferingTransformerInterface::KEY_OFFERING_ID => 9000,
            ListingInventoryProductOfferingTransformerInterface::KEY_QUANTITY => 5,
            ListingInventoryProductOfferingTransformerInterface::KEY_IS_ENABLED => true,
            ListingInventoryProductOfferingTransformerInterface::KEY_IS_DELETED => false,
            ListingInventoryProductOfferingTransformerInterface::KEY_PRICE => $priceData,
            ListingInventoryProductOfferingTransformerInterface::KEY_READINESS_STATE_ID => 7,
        ];

        $moneyTransformer = self::createMock(MoneyTransformerInterface::class);
        $moneyTransformer->expects(self::once())->method('transform')
            ->with($priceData)
            ->willReturn($price);

        $transformer = new ListingInventoryProductOfferingTransformer($moneyTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getOfferingId());
        self::assertSame(5, $actual->getQuantity());
        self::assertTrue($actual->getIsEnabled());
        self::assertFalse($actual->getIsDeleted());
        self::assertSame($price, $actual->getPrice());
        self::assertSame(7, $actual->getReadinessStateId());
    }

    /**
     * @param array<string, mixed>                                    $data
     * @param Closure(ListingInventoryProductOfferingInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $moneyTransformer = self::createStub(MoneyTransformerInterface::class);

        $transformer = new ListingInventoryProductOfferingTransformer($moneyTransformer);

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ListingInventoryProductOfferingInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ListingInventoryProductOfferingTransformerInterface::KEY_OFFERING_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ListingInventoryProductOfferingInterface $m): void {
                self::assertNull($m->getQuantity());
                self::assertNull($m->getIsEnabled());
                self::assertNull($m->getIsDeleted());
                self::assertNull($m->getPrice());
                self::assertNull($m->getReadinessStateId());
            },
        ];

        yield 'quantityZero' => [[$id => 1, ListingInventoryProductOfferingTransformerInterface::KEY_QUANTITY => 0], static function (ListingInventoryProductOfferingInterface $m): void {
            self::assertSame(0, $m->getQuantity());
        }];
        yield 'quantityWrongType' => [[$id => 1, ListingInventoryProductOfferingTransformerInterface::KEY_QUANTITY => 'x'], static function (ListingInventoryProductOfferingInterface $m): void {
            self::assertNull($m->getQuantity());
        }];
        yield 'isEnabledWrongType' => [[$id => 1, ListingInventoryProductOfferingTransformerInterface::KEY_IS_ENABLED => 'x'], static function (ListingInventoryProductOfferingInterface $m): void {
            self::assertNull($m->getIsEnabled());
        }];
        yield 'isDeletedWrongType' => [[$id => 1, ListingInventoryProductOfferingTransformerInterface::KEY_IS_DELETED => 'x'], static function (ListingInventoryProductOfferingInterface $m): void {
            self::assertNull($m->getIsDeleted());
        }];
        yield 'priceWrongType' => [[$id => 1, ListingInventoryProductOfferingTransformerInterface::KEY_PRICE => 'x'], static function (ListingInventoryProductOfferingInterface $m): void {
            self::assertNull($m->getPrice());
        }];
        yield 'readinessStateIdZero' => [[$id => 1, ListingInventoryProductOfferingTransformerInterface::KEY_READINESS_STATE_ID => 0], static function (ListingInventoryProductOfferingInterface $m): void {
            self::assertSame(0, $m->getReadinessStateId());
        }];
        yield 'readinessStateIdWrongType' => [[$id => 1, ListingInventoryProductOfferingTransformerInterface::KEY_READINESS_STATE_ID => 'x'], static function (ListingInventoryProductOfferingInterface $m): void {
            self::assertNull($m->getReadinessStateId());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ListingInventoryProductOfferingTransformerInterface::KEY_OFFERING_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidOfferingId(array $data): void
    {
        $transformer = new ListingInventoryProductOfferingTransformer(self::createStub(MoneyTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingInventoryProductOfferingTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ListingInventoryProductOfferingTransformerInterface::KEY_OFFERING_ID));

        $transformer->transform($data);
    }
}
