<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryProduct;
use ChristianBrown\Etsy\Model\ListingInventoryProductInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;
use ChristianBrown\Etsy\Model\ListingPropertyValueInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductTransformer;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingInventoryProduct::class)]
#[CoversClass(ListingInventoryProductTransformer::class)]
final class ListingInventoryProductTransformerTest extends TestCase
{
    public function testSetProductId(): void
    {
        $product = new ListingInventoryProduct(1);

        self::assertSame(2, $product->setProductId(2)->getProductId());
    }

    public function testTransform(): void
    {
        $offeringsData = [['__offering__']];
        $offerings = [self::createStub(ListingInventoryProductOfferingInterface::class)];
        $propertyValuesData = [['__property__']];
        $propertyValues = [self::createStub(ListingPropertyValueInterface::class)];

        $data = [
            ListingInventoryProductTransformerInterface::KEY_PRODUCT_ID => 9000,
            ListingInventoryProductTransformerInterface::KEY_SKU => 'SKU-1',
            ListingInventoryProductTransformerInterface::KEY_IS_DELETED => true,
            ListingInventoryProductTransformerInterface::KEY_OFFERINGS => $offeringsData,
            ListingInventoryProductTransformerInterface::KEY_PROPERTY_VALUES => $propertyValuesData,
        ];

        $offeringsTransformer = self::createMock(ListingInventoryProductOfferingsTransformerInterface::class);
        $offeringsTransformer->expects(self::once())->method('transform')
            ->with($offeringsData)
            ->willReturn($offerings);

        $propertyValuesTransformer = self::createMock(ListingPropertyValuesTransformerInterface::class);
        $propertyValuesTransformer->expects(self::once())->method('transform')
            ->with($propertyValuesData)
            ->willReturn($propertyValues);

        $transformer = new ListingInventoryProductTransformer($offeringsTransformer, $propertyValuesTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getProductId());
        self::assertSame('SKU-1', $actual->getSku());
        self::assertTrue($actual->getIsDeleted());
        self::assertSame($offerings, $actual->getOfferings());
        self::assertSame($propertyValues, $actual->getPropertyValues());
    }

    /**
     * @param array<string, mixed>                            $data
     * @param Closure(ListingInventoryProductInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $offeringsTransformer = self::createStub(ListingInventoryProductOfferingsTransformerInterface::class);
        $propertyValuesTransformer = self::createStub(ListingPropertyValuesTransformerInterface::class);

        $transformer = new ListingInventoryProductTransformer($offeringsTransformer, $propertyValuesTransformer);

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ListingInventoryProductInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ListingInventoryProductTransformerInterface::KEY_PRODUCT_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ListingInventoryProductInterface $m): void {
                self::assertNull($m->getSku());
                self::assertNull($m->getIsDeleted());
                self::assertSame([], $m->getOfferings());
                self::assertSame([], $m->getPropertyValues());
            },
        ];

        yield 'skuWrongType' => [[$id => 1, ListingInventoryProductTransformerInterface::KEY_SKU => 42], static function (ListingInventoryProductInterface $m): void {
            self::assertNull($m->getSku());
        }];
        yield 'isDeletedWrongType' => [[$id => 1, ListingInventoryProductTransformerInterface::KEY_IS_DELETED => 'x'], static function (ListingInventoryProductInterface $m): void {
            self::assertNull($m->getIsDeleted());
        }];
        yield 'offeringsEmpty' => [[$id => 1, ListingInventoryProductTransformerInterface::KEY_OFFERINGS => []], static function (ListingInventoryProductInterface $m): void {
            self::assertSame([], $m->getOfferings());
        }];
        yield 'offeringsWrongType' => [[$id => 1, ListingInventoryProductTransformerInterface::KEY_OFFERINGS => 'x'], static function (ListingInventoryProductInterface $m): void {
            self::assertSame([], $m->getOfferings());
        }];
        yield 'propertyValuesEmpty' => [[$id => 1, ListingInventoryProductTransformerInterface::KEY_PROPERTY_VALUES => []], static function (ListingInventoryProductInterface $m): void {
            self::assertSame([], $m->getPropertyValues());
        }];
        yield 'propertyValuesWrongType' => [[$id => 1, ListingInventoryProductTransformerInterface::KEY_PROPERTY_VALUES => 'x'], static function (ListingInventoryProductInterface $m): void {
            self::assertSame([], $m->getPropertyValues());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ListingInventoryProductTransformerInterface::KEY_PRODUCT_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidProductId(array $data): void
    {
        $transformer = new ListingInventoryProductTransformer(
            self::createStub(ListingInventoryProductOfferingsTransformerInterface::class),
            self::createStub(ListingPropertyValuesTransformerInterface::class),
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingInventoryProductTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ListingInventoryProductTransformerInterface::KEY_PRODUCT_ID));

        $transformer->transform($data);
    }
}
