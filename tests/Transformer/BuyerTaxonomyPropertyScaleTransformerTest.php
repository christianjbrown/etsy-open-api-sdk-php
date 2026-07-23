<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyScale;
use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyScaleInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyScaleTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyPropertyScaleTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(BuyerTaxonomyPropertyScale::class)]
#[CoversClass(BuyerTaxonomyPropertyScaleTransformer::class)]
final class BuyerTaxonomyPropertyScaleTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            BuyerTaxonomyPropertyScaleTransformerInterface::KEY_DESCRIPTION => 'desc',
            BuyerTaxonomyPropertyScaleTransformerInterface::KEY_DISPLAY_NAME => 'dn',
            BuyerTaxonomyPropertyScaleTransformerInterface::KEY_SCALE_ID => 9,
        ];

        $transformer = new BuyerTaxonomyPropertyScaleTransformer();

        $actual = $transformer->transform($data);

        self::assertSame('desc', $actual->getDescription());
        self::assertSame('dn', $actual->getDisplayName());
        self::assertSame(9, $actual->getScaleId());
    }

    /**
     * @param array<string, mixed>                               $data
     * @param Closure(BuyerTaxonomyPropertyScaleInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new BuyerTaxonomyPropertyScaleTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(BuyerTaxonomyPropertyScaleInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        yield 'allOptionalAbsent' => [
            [],
            static function (BuyerTaxonomyPropertyScaleInterface $m): void {
                self::assertNull($m->getDescription());
                self::assertNull($m->getDisplayName());
                self::assertNull($m->getScaleId());
            },
        ];

        yield 'descriptionWrongType' => [[BuyerTaxonomyPropertyScaleTransformerInterface::KEY_DESCRIPTION => 42], static function (BuyerTaxonomyPropertyScaleInterface $m): void {
            self::assertNull($m->getDescription());
        }];
        yield 'displayNameWrongType' => [[BuyerTaxonomyPropertyScaleTransformerInterface::KEY_DISPLAY_NAME => 42], static function (BuyerTaxonomyPropertyScaleInterface $m): void {
            self::assertNull($m->getDisplayName());
        }];
        yield 'scaleIdWrongType' => [[BuyerTaxonomyPropertyScaleTransformerInterface::KEY_SCALE_ID => 'x'], static function (BuyerTaxonomyPropertyScaleInterface $m): void {
            self::assertNull($m->getScaleId());
        }];
    }
}
