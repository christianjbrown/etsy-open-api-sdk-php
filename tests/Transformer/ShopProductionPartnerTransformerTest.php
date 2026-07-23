<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopProductionPartner;
use ChristianBrown\Etsy\Model\ShopProductionPartnerInterface;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnerTransformer;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnerTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopProductionPartner::class)]
#[CoversClass(ShopProductionPartnerTransformer::class)]
final class ShopProductionPartnerTransformerTest extends TestCase
{
    public function testSetProductionPartnerId(): void
    {
        $shopProductionPartner = new ShopProductionPartner(1);

        self::assertSame(2, $shopProductionPartner->setProductionPartnerId(2)->getProductionPartnerId());
    }

    public function testTransform(): void
    {
        $data = [
            ShopProductionPartnerTransformerInterface::KEY_PRODUCTION_PARTNER_ID => 9000,
            ShopProductionPartnerTransformerInterface::KEY_LOCATION => 'v_location',
            ShopProductionPartnerTransformerInterface::KEY_PARTNER_NAME => 'v_partnerName',
        ];

        $transformer = new ShopProductionPartnerTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getProductionPartnerId());
        self::assertSame('v_location', $actual->getLocation());
        self::assertSame('v_partnerName', $actual->getPartnerName());
    }

    /**
     * @param array<string, mixed>                          $data
     * @param Closure(ShopProductionPartnerInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ShopProductionPartnerTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ShopProductionPartnerInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ShopProductionPartnerTransformerInterface::KEY_PRODUCTION_PARTNER_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ShopProductionPartnerInterface $model): void {
                self::assertNull($model->getLocation());
                self::assertNull($model->getPartnerName());
            },
        ];

        yield 'locationWrongType' => [[$id => 1, ShopProductionPartnerTransformerInterface::KEY_LOCATION => 42], static function (ShopProductionPartnerInterface $m): void {
            self::assertNull($m->getLocation());
        }];
        yield 'partnerNameWrongType' => [[$id => 1, ShopProductionPartnerTransformerInterface::KEY_PARTNER_NAME => 42], static function (ShopProductionPartnerInterface $m): void {
            self::assertNull($m->getPartnerName());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ShopProductionPartnerTransformerInterface::KEY_PRODUCTION_PARTNER_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidProductionPartnerId(array $data): void
    {
        $transformer = new ShopProductionPartnerTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopProductionPartnerTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ShopProductionPartnerTransformerInterface::KEY_PRODUCTION_PARTNER_ID));

        $transformer->transform($data);
    }
}
