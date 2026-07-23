<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TaxonomyNodeProperty;
use ChristianBrown\Etsy\Model\TaxonomyNodePropertyInterface;
use ChristianBrown\Etsy\Model\TaxonomyPropertyScaleInterface;
use ChristianBrown\Etsy\Model\TaxonomyPropertyValueInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertyTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyNodePropertyTransformerInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyScalesTransformerInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyValuesTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(TaxonomyNodeProperty::class)]
#[CoversClass(TaxonomyNodePropertyTransformer::class)]
final class TaxonomyNodePropertyTransformerTest extends TestCase
{
    public function testSetPropertyId(): void
    {
        $property = new TaxonomyNodeProperty(1);

        self::assertSame(2, $property->setPropertyId(2)->getPropertyId());
    }

    public function testTransform(): void
    {
        $possibleValuesData = [['__possible__']];
        $selectedValuesData = [['__selected__']];
        $scalesData = [['__scale__']];

        $possibleValues = [self::createStub(TaxonomyPropertyValueInterface::class)];
        $selectedValues = [self::createStub(TaxonomyPropertyValueInterface::class)];
        $scales = [self::createStub(TaxonomyPropertyScaleInterface::class)];

        $data = [
            TaxonomyNodePropertyTransformerInterface::KEY_PROPERTY_ID => 5,
            TaxonomyNodePropertyTransformerInterface::KEY_DISPLAY_NAME => 'dn',
            TaxonomyNodePropertyTransformerInterface::KEY_IS_MULTIVALUED => true,
            TaxonomyNodePropertyTransformerInterface::KEY_IS_REQUIRED => false,
            TaxonomyNodePropertyTransformerInterface::KEY_MAX_VALUES_ALLOWED => 3,
            TaxonomyNodePropertyTransformerInterface::KEY_NAME => 'n',
            TaxonomyNodePropertyTransformerInterface::KEY_POSSIBLE_VALUES => $possibleValuesData,
            TaxonomyNodePropertyTransformerInterface::KEY_SCALES => $scalesData,
            TaxonomyNodePropertyTransformerInterface::KEY_SELECTED_VALUES => $selectedValuesData,
            TaxonomyNodePropertyTransformerInterface::KEY_SUPPORTS_ATTRIBUTES => true,
            TaxonomyNodePropertyTransformerInterface::KEY_SUPPORTS_VARIATIONS => false,
        ];

        $scalesTransformer = self::createMock(TaxonomyPropertyScalesTransformerInterface::class);
        $scalesTransformer->expects(self::once())->method('transform')
            ->with($scalesData)
            ->willReturn($scales);

        $valuesTransformer = self::createStub(TaxonomyPropertyValuesTransformerInterface::class);
        $valuesTransformer->method('transform')
            ->willReturnMap(
                [
                    [$possibleValuesData, $possibleValues],
                    [$selectedValuesData, $selectedValues],
                ]
            );

        $transformer = new TaxonomyNodePropertyTransformer($scalesTransformer, $valuesTransformer);

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
     * @param array<string, mixed>                         $data
     * @param Closure(TaxonomyNodePropertyInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $scalesTransformer = self::createStub(TaxonomyPropertyScalesTransformerInterface::class);
        $valuesTransformer = self::createStub(TaxonomyPropertyValuesTransformerInterface::class);

        $transformer = new TaxonomyNodePropertyTransformer($scalesTransformer, $valuesTransformer);

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(TaxonomyNodePropertyInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = TaxonomyNodePropertyTransformerInterface::KEY_PROPERTY_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (TaxonomyNodePropertyInterface $m): void {
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

        yield 'displayNameWrongType' => [[$id => 1, TaxonomyNodePropertyTransformerInterface::KEY_DISPLAY_NAME => 42], static function (TaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getDisplayName());
        }];
        yield 'isMultivaluedWrongType' => [[$id => 1, TaxonomyNodePropertyTransformerInterface::KEY_IS_MULTIVALUED => 'x'], static function (TaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getIsMultivalued());
        }];
        yield 'isRequiredWrongType' => [[$id => 1, TaxonomyNodePropertyTransformerInterface::KEY_IS_REQUIRED => 'x'], static function (TaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getIsRequired());
        }];
        yield 'maxValuesAllowedWrongType' => [[$id => 1, TaxonomyNodePropertyTransformerInterface::KEY_MAX_VALUES_ALLOWED => 'x'], static function (TaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getMaxValuesAllowed());
        }];
        yield 'nameWrongType' => [[$id => 1, TaxonomyNodePropertyTransformerInterface::KEY_NAME => 42], static function (TaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getName());
        }];
        yield 'possibleValuesWrongType' => [[$id => 1, TaxonomyNodePropertyTransformerInterface::KEY_POSSIBLE_VALUES => 'x'], static function (TaxonomyNodePropertyInterface $m): void {
            self::assertSame([], $m->getPossibleValues());
        }];
        yield 'scalesWrongType' => [[$id => 1, TaxonomyNodePropertyTransformerInterface::KEY_SCALES => 'x'], static function (TaxonomyNodePropertyInterface $m): void {
            self::assertSame([], $m->getScales());
        }];
        yield 'selectedValuesWrongType' => [[$id => 1, TaxonomyNodePropertyTransformerInterface::KEY_SELECTED_VALUES => 'x'], static function (TaxonomyNodePropertyInterface $m): void {
            self::assertSame([], $m->getSelectedValues());
        }];
        yield 'supportsAttributesWrongType' => [[$id => 1, TaxonomyNodePropertyTransformerInterface::KEY_SUPPORTS_ATTRIBUTES => 'x'], static function (TaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getSupportsAttributes());
        }];
        yield 'supportsVariationsWrongType' => [[$id => 1, TaxonomyNodePropertyTransformerInterface::KEY_SUPPORTS_VARIATIONS => 'x'], static function (TaxonomyNodePropertyInterface $m): void {
            self::assertNull($m->getSupportsVariations());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[TaxonomyNodePropertyTransformerInterface::KEY_PROPERTY_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidPropertyId(array $data): void
    {
        $transformer = new TaxonomyNodePropertyTransformer(
            self::createStub(TaxonomyPropertyScalesTransformerInterface::class),
            self::createStub(TaxonomyPropertyValuesTransformerInterface::class),
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(TaxonomyNodePropertyTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, TaxonomyNodePropertyTransformerInterface::KEY_PROPERTY_ID));

        $transformer->transform($data);
    }
}
