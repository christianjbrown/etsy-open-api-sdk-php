<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\TaxonomyPropertyScale;
use ChristianBrown\Etsy\Model\TaxonomyPropertyScaleInterface;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyScaleTransformer;
use ChristianBrown\Etsy\Transformer\TaxonomyPropertyScaleTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(TaxonomyPropertyScale::class)]
#[CoversClass(TaxonomyPropertyScaleTransformer::class)]
final class TaxonomyPropertyScaleTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            TaxonomyPropertyScaleTransformerInterface::KEY_DESCRIPTION => 'desc',
            TaxonomyPropertyScaleTransformerInterface::KEY_DISPLAY_NAME => 'dn',
            TaxonomyPropertyScaleTransformerInterface::KEY_SCALE_ID => 9,
        ];

        $transformer = new TaxonomyPropertyScaleTransformer();

        $actual = $transformer->transform($data);

        self::assertSame('desc', $actual->getDescription());
        self::assertSame('dn', $actual->getDisplayName());
        self::assertSame(9, $actual->getScaleId());
    }

    /**
     * @param array<string, mixed>                          $data
     * @param Closure(TaxonomyPropertyScaleInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new TaxonomyPropertyScaleTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(TaxonomyPropertyScaleInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        yield 'allOptionalAbsent' => [
            [],
            static function (TaxonomyPropertyScaleInterface $m): void {
                self::assertNull($m->getDescription());
                self::assertNull($m->getDisplayName());
                self::assertNull($m->getScaleId());
            },
        ];

        yield 'descriptionWrongType' => [[TaxonomyPropertyScaleTransformerInterface::KEY_DESCRIPTION => 42], static function (TaxonomyPropertyScaleInterface $m): void {
            self::assertNull($m->getDescription());
        }];
        yield 'displayNameWrongType' => [[TaxonomyPropertyScaleTransformerInterface::KEY_DISPLAY_NAME => 42], static function (TaxonomyPropertyScaleInterface $m): void {
            self::assertNull($m->getDisplayName());
        }];
        yield 'scaleIdWrongType' => [[TaxonomyPropertyScaleTransformerInterface::KEY_SCALE_ID => 'x'], static function (TaxonomyPropertyScaleInterface $m): void {
            self::assertNull($m->getScaleId());
        }];
    }
}
