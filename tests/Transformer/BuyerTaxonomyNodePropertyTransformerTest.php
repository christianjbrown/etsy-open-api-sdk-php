<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodeProperty;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodePropertyInterface;
use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyScaleInterface;
use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyValueInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertyTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodePropertyTransformerInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyScalesTransformerInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyValuesTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(BuyerTaxonomyNodeProperty::class)]
#[CoversClass(BuyerTaxonomyNodePropertyTransformer::class)]
final class BuyerTaxonomyNodePropertyTransformerTest extends TestCase
{
    public function testSetPropertyId(): void
    {
        $property = new BuyerTaxonomyNodeProperty(1);

        self::assertSame(2, $property->setPropertyId(2)->getPropertyId());
    }

    public function testTransform(): void
    {
        $possibleValuesData = [['__possible__']];
        $selectedValuesData = [['__selected__']];
        $scalesData = [['__scale__']];

        $possibleValues = [self::createStub(BuyerTaxonomyPropertyValueInterface::class)];
        $selectedValues = [self::createStub(BuyerTaxonomyPropertyValueInterface::class)];
        $scales = [self::createStub(BuyerTaxonomyPropertyScaleInterface::class)];

        $data = [
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_PROPERTY_ID => 5,
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_DISPLAY_NAME => 'dn',
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_IS_MULTIVALUED => true,
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_IS_REQUIRED => false,
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_MAX_VALUES_ALLOWED => 3,
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_NAME => 'n',
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_POSSIBLE_VALUES => $possibleValuesData,
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_SCALES => $scalesData,
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_SELECTED_VALUES => $selectedValuesData,
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_SUPPORTS_ATTRIBUTES => true,
            BuyerTaxonomyNodePropertyTransformerInterface::KEY_SUPPORTS_VARIATIONS => false,
        ];

        $scalesTransformer = self::createMock(BuyerTaxonomyPropertyScalesTransformerInterface::class);
        $scalesTransformer->expects(self::once())->method('transform')
            ->with($scalesData)
            ->willReturn($scales);

        $valuesTransformer = self::createStub(BuyerTaxonomyPropertyValuesTransformerInterface::class);
        $valuesTransformer->method('transform')
            ->willReturnMap(
                [
                    [$possibleValuesData, $possibleValues],
                    [$selectedValuesData, $selectedValues],
                ]
            );

        $transformer = new BuyerTaxonomyNodePropertyTransformer($scalesTransformer, $valuesTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(5, $actual->getPropertyId());
        self::assertSame('dn', $actual->getDisplayName());
        self::assertTrue($actual->getIsMultivalued());
        self::assertFalse($actual->getIsRequired());
        self::assertSame(3, $actual->getMaxValuesAllowed());
        self::assertSame('n', $actual->getName());
        self::assertSame($possibleValues, $actual->getPossibleValues());
        self::assertSame($scales, $actual->getScales());
        self::assertSame($selectedValues, $actual->getSelectedValues());
        self::assertTrue($actual->getSupportsAttributes());
        self::assertFalse($actual->getSupportsVariations());
    }

    /**
     * @param array<string, mixed>                              $data
     * @param Closure(BuyerTaxonomyNodePropertyInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $scalesTransformer = self::createStub(BuyerTaxonomyPropertyScalesTransformerInterface::class);
        $valuesTransformer = self::createStub(BuyerTaxonomyPropertyValuesTransformerInterface::class);

        $transformer = new BuyerTaxonomyNodePropertyTransformer($scalesTransformer, $valuesTransformer);

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(BuyerTaxonomyNodePropertyInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = BuyerTaxonomyNodePropertyTransformerInterface::KEY_PROPERTY_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (BuyerTaxonomyNodePropertyInterface $m): void {
                self::assertNull($m->getDisplayName());
                self::assertNull($m->getIsMultivalued());
                self::assertNull($m->getIsRequired());
                self::assertNull($m->getMaxValuesAllowed());
                self::assertNull($m->getName());
                self::assertSame([], $m->getPossibleValues());
                self::assertSame([], $m->getScales());
                self::assertSame([], $m->getSelectedValues());
                self::assertNull($m->getSupportsAttributes());
                self::assertNull($m->getSupportsVariations());
            },
        ];

        yield 'displayNameWrongType' => [[$id => 1, BuyerTaxonomyNodePropertyTransformerInterface::KEY_DISPLAY_NAME => 42], static function (BuyerTaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getDisplayName());
        }];
        yield 'isMultivaluedWrongType' => [[$id => 1, BuyerTaxonomyNodePropertyTransformerInterface::KEY_IS_MULTIVALUED => 'x'], static function (BuyerTaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getIsMultivalued());
        }];
        yield 'isRequiredWrongType' => [[$id => 1, BuyerTaxonomyNodePropertyTransformerInterface::KEY_IS_REQUIRED => 'x'], static function (BuyerTaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getIsRequired());
        }];
        yield 'maxValuesAllowedWrongType' => [[$id => 1, BuyerTaxonomyNodePropertyTransformerInterface::KEY_MAX_VALUES_ALLOWED => 'x'], static function (BuyerTaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getMaxValuesAllowed());
        }];
        yield 'nameWrongType' => [[$id => 1, BuyerTaxonomyNodePropertyTransformerInterface::KEY_NAME => 42], static function (BuyerTaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getName());
        }];
        yield 'possibleValuesWrongType' => [[$id => 1, BuyerTaxonomyNodePropertyTransformerInterface::KEY_POSSIBLE_VALUES => 'x'], static function (BuyerTaxonomyNodePropertyInterface $m): void {
            self::assertSame([], $m->getPossibleValues());
        }];
        yield 'scalesWrongType' => [[$id => 1, BuyerTaxonomyNodePropertyTransformerInterface::KEY_SCALES => 'x'], static function (BuyerTaxonomyNodePropertyInterface $m): void {
            self::assertSame([], $m->getScales());
        }];
        yield 'selectedValuesWrongType' => [[$id => 1, BuyerTaxonomyNodePropertyTransformerInterface::KEY_SELECTED_VALUES => 'x'], static function (BuyerTaxonomyNodePropertyInterface $m): void {
            self::assertSame([], $m->getSelectedValues());
        }];
        yield 'supportsAttributesWrongType' => [[$id => 1, BuyerTaxonomyNodePropertyTransformerInterface::KEY_SUPPORTS_ATTRIBUTES => 'x'], static function (BuyerTaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getSupportsAttributes());
        }];
        yield 'supportsVariationsWrongType' => [[$id => 1, BuyerTaxonomyNodePropertyTransformerInterface::KEY_SUPPORTS_VARIATIONS => 'x'], static function (BuyerTaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getSupportsVariations());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[BuyerTaxonomyNodePropertyTransformerInterface::KEY_PROPERTY_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidPropertyId(array $data): void
    {
        $transformer = new BuyerTaxonomyNodePropertyTransformer(
            self::createStub(BuyerTaxonomyPropertyScalesTransformerInterface::class),
            self::createStub(BuyerTaxonomyPropertyValuesTransformerInterface::class),
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyNodePropertyTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, BuyerTaxonomyNodePropertyTransformerInterface::KEY_PROPERTY_ID));

        $transformer->transform($data);
    }
}
