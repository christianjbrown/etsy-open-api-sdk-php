<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopSection;
use ChristianBrown\Etsy\Model\ShopSectionInterface;
use ChristianBrown\Etsy\Transformer\ShopSectionTransformer;
use ChristianBrown\Etsy\Transformer\ShopSectionTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopSection::class)]
#[CoversClass(ShopSectionTransformer::class)]
final class ShopSectionTransformerTest extends TestCase
{
    public function testSetShopSectionId(): void
    {
        $shopSection = new ShopSection(1);

        self::assertSame(2, $shopSection->setShopSectionId(2)->getShopSectionId());
    }

    public function testTransform(): void
    {
        $data = [
            ShopSectionTransformerInterface::KEY_SHOP_SECTION_ID => 9000,
            ShopSectionTransformerInterface::KEY_ACTIVE_LISTING_COUNT => 100,
            ShopSectionTransformerInterface::KEY_RANK => 101,
            ShopSectionTransformerInterface::KEY_TITLE => 'v_title',
            ShopSectionTransformerInterface::KEY_USER_ID => 102,
        ];

        $transformer = new ShopSectionTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getShopSectionId());
        self::assertSame(100, $actual->getActiveListingCount());
        self::assertSame(101, $actual->getRank());
        self::assertSame('v_title', $actual->getTitle());
        self::assertSame(102, $actual->getUserId());
    }

    /**
     * @param array<string, mixed>                $data
     * @param Closure(ShopSectionInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ShopSectionTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ShopSectionInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ShopSectionTransformerInterface::KEY_SHOP_SECTION_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ShopSectionInterface $model): void {
                self::assertNull($model->getActiveListingCount());
                self::assertNull($model->getRank());
                self::assertNull($model->getTitle());
                self::assertNull($model->getUserId());
            },
        ];

        yield 'activeListingCountWrongType' => [[$id => 1, ShopSectionTransformerInterface::KEY_ACTIVE_LISTING_COUNT => 'x'], static function (ShopSectionInterface $m): void {
            self::assertNull($m->getActiveListingCount());
        }];
        yield 'rankWrongType' => [[$id => 1, ShopSectionTransformerInterface::KEY_RANK => 'x'], static function (ShopSectionInterface $m): void {
            self::assertNull($m->getRank());
        }];
        yield 'titleWrongType' => [[$id => 1, ShopSectionTransformerInterface::KEY_TITLE => 42], static function (ShopSectionInterface $m): void {
            self::assertNull($m->getTitle());
        }];
        yield 'userIdWrongType' => [[$id => 1, ShopSectionTransformerInterface::KEY_USER_ID => 'x'], static function (ShopSectionInterface $m): void {
            self::assertNull($m->getUserId());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ShopSectionTransformerInterface::KEY_SHOP_SECTION_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidShopSectionId(array $data): void
    {
        $transformer = new ShopSectionTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopSectionTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ShopSectionTransformerInterface::KEY_SHOP_SECTION_ID));

        $transformer->transform($data);
    }
}
