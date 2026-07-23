<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\ListingVariationImage;
use ChristianBrown\Etsy\Transformer\ListingVariationImageTransformer;
use ChristianBrown\Etsy\Transformer\ListingVariationImageTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingVariationImage::class)]
#[CoversClass(ListingVariationImageTransformer::class)]
final class ListingVariationImageTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            ListingVariationImageTransformerInterface::KEY_IMAGE_ID => 100,
            ListingVariationImageTransformerInterface::KEY_PROPERTY_ID => 200,
            ListingVariationImageTransformerInterface::KEY_VALUE => 'v_value',
            ListingVariationImageTransformerInterface::KEY_VALUE_ID => 300,
        ];

        $transformer = new ListingVariationImageTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(100, $actual->getImageId());
        self::assertSame(200, $actual->getPropertyId());
        self::assertSame('v_value', $actual->getValue());
        self::assertSame(300, $actual->getValueId());
    }

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, ?int $expectedImageId, ?int $expectedPropertyId, ?string $expectedValue, ?int $expectedValueId): void
    {
        $transformer = new ListingVariationImageTransformer();

        $actual = $transformer->transform($data);

        self::assertSame($expectedImageId, $actual->getImageId());
        self::assertSame($expectedPropertyId, $actual->getPropertyId());
        self::assertSame($expectedValue, $actual->getValue());
        self::assertSame($expectedValueId, $actual->getValueId());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, ?int, ?int, ?string, ?int}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $imageId = ListingVariationImageTransformerInterface::KEY_IMAGE_ID;
        $propertyId = ListingVariationImageTransformerInterface::KEY_PROPERTY_ID;
        $value = ListingVariationImageTransformerInterface::KEY_VALUE;
        $valueId = ListingVariationImageTransformerInterface::KEY_VALUE_ID;

        yield 'allAbsent' => [[], null, null, null, null];
        yield 'imageIdZero' => [[$imageId => 0], 0, null, null, null];
        yield 'imageIdWrongType' => [[$imageId => 'not-int'], null, null, null, null];
        yield 'propertyIdZero' => [[$propertyId => 0], null, 0, null, null];
        yield 'propertyIdWrongType' => [[$propertyId => 'not-int'], null, null, null, null];
        yield 'valueWrongType' => [[$value => 42], null, null, null, null];
        yield 'valueIdZero' => [[$valueId => 0], null, null, null, 0];
        yield 'valueIdWrongType' => [[$valueId => 'not-int'], null, null, null, null];
    }
}
