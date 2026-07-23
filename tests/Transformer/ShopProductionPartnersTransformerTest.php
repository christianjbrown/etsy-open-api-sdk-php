<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopProductionPartnerInterface;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnersTransformer;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnersTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnerTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopProductionPartnersTransformer::class)]
final class ShopProductionPartnersTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['partner-1'], ['partner-2']];

        $partner1 = self::createStub(ShopProductionPartnerInterface::class);
        $partner2 = self::createStub(ShopProductionPartnerInterface::class);

        $shopProductionPartnerTransformer = self::createStub(ShopProductionPartnerTransformerInterface::class);
        $shopProductionPartnerTransformer->method('transform')
            ->willReturnMap(
                [
                    [['partner-1'], $partner1],
                    [['partner-2'], $partner2],
                ]
            );

        $transformer = new ShopProductionPartnersTransformer($shopProductionPartnerTransformer);

        self::assertSame([$partner1, $partner2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $shopProductionPartnerTransformer = self::createStub(ShopProductionPartnerTransformerInterface::class);

        $transformer = new ShopProductionPartnersTransformer($shopProductionPartnerTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $shopProductionPartnerTransformer = self::createStub(ShopProductionPartnerTransformerInterface::class);

        $transformer = new ShopProductionPartnersTransformer($shopProductionPartnerTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopProductionPartnersTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShopProductionPartnersTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
