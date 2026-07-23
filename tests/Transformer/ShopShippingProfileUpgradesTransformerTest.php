<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradesTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradeTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopShippingProfileUpgradesTransformer::class)]
final class ShopShippingProfileUpgradesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['upgrade-1'], ['upgrade-2']];

        $upgrade1 = self::createStub(ShopShippingProfileUpgradeInterface::class);
        $upgrade2 = self::createStub(ShopShippingProfileUpgradeInterface::class);

        $upgradeTransformer = self::createStub(ShopShippingProfileUpgradeTransformerInterface::class);
        $upgradeTransformer->method('transform')
            ->willReturnMap(
                [
                    [['upgrade-1'], $upgrade1],
                    [['upgrade-2'], $upgrade2],
                ]
            );

        $transformer = new ShopShippingProfileUpgradesTransformer($upgradeTransformer);

        self::assertSame([$upgrade1, $upgrade2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $upgradeTransformer = self::createStub(ShopShippingProfileUpgradeTransformerInterface::class);

        $transformer = new ShopShippingProfileUpgradesTransformer($upgradeTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $upgradeTransformer = self::createStub(ShopShippingProfileUpgradeTransformerInterface::class);

        $transformer = new ShopShippingProfileUpgradesTransformer($upgradeTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopShippingProfileUpgradesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShopShippingProfileUpgradesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
