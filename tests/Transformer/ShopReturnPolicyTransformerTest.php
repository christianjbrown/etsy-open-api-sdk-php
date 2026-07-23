<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReturnPolicy;
use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPolicyTransformer;
use ChristianBrown\Etsy\Transformer\ShopReturnPolicyTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopReturnPolicy::class)]
#[CoversClass(ShopReturnPolicyTransformer::class)]
final class ShopReturnPolicyTransformerTest extends TestCase
{
    public function testSetReturnPolicyId(): void
    {
        $shopReturnPolicy = new ShopReturnPolicy(1);

        self::assertSame(2, $shopReturnPolicy->setReturnPolicyId(2)->getReturnPolicyId());
    }

    public function testTransform(): void
    {
        $data = [
            ShopReturnPolicyTransformerInterface::KEY_RETURN_POLICY_ID => 9000,
            ShopReturnPolicyTransformerInterface::KEY_ACCEPTS_EXCHANGES => true,
            ShopReturnPolicyTransformerInterface::KEY_ACCEPTS_RETURNS => true,
            ShopReturnPolicyTransformerInterface::KEY_RETURN_DEADLINE => 30,
            ShopReturnPolicyTransformerInterface::KEY_SHOP_ID => 100,
        ];

        $transformer = new ShopReturnPolicyTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getReturnPolicyId());
        self::assertTrue($actual->getAcceptsExchanges());
        self::assertTrue($actual->getAcceptsReturns());
        self::assertSame(30, $actual->getReturnDeadline());
        self::assertSame(100, $actual->getShopId());
    }

    /**
     * @param array<string, mixed>                     $data
     * @param Closure(ShopReturnPolicyInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ShopReturnPolicyTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ShopReturnPolicyInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ShopReturnPolicyTransformerInterface::KEY_RETURN_POLICY_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ShopReturnPolicyInterface $model): void {
                self::assertNull($model->getAcceptsExchanges());
                self::assertNull($model->getAcceptsReturns());
                self::assertNull($model->getReturnDeadline());
                self::assertNull($model->getShopId());
            },
        ];

        yield 'acceptsExchangesWrongType' => [[$id => 1, ShopReturnPolicyTransformerInterface::KEY_ACCEPTS_EXCHANGES => 'x'], static function (ShopReturnPolicyInterface $m): void {
            self::assertNull($m->getAcceptsExchanges());
        }];
        yield 'acceptsReturnsWrongType' => [[$id => 1, ShopReturnPolicyTransformerInterface::KEY_ACCEPTS_RETURNS => 'x'], static function (ShopReturnPolicyInterface $m): void {
            self::assertNull($m->getAcceptsReturns());
        }];
        yield 'returnDeadlineWrongType' => [[$id => 1, ShopReturnPolicyTransformerInterface::KEY_RETURN_DEADLINE => 'x'], static function (ShopReturnPolicyInterface $m): void {
            self::assertNull($m->getReturnDeadline());
        }];
        yield 'shopIdWrongType' => [[$id => 1, ShopReturnPolicyTransformerInterface::KEY_SHOP_ID => 'x'], static function (ShopReturnPolicyInterface $m): void {
            self::assertNull($m->getShopId());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ShopReturnPolicyTransformerInterface::KEY_RETURN_POLICY_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidReturnPolicyId(array $data): void
    {
        $transformer = new ShopReturnPolicyTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReturnPolicyTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ShopReturnPolicyTransformerInterface::KEY_RETURN_POLICY_ID));

        $transformer->transform($data);
    }
}
