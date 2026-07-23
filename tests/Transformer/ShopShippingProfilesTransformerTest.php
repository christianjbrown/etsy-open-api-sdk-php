<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfilesTransformer;
use ChristianBrown\Etsy\Transformer\ShopShippingProfilesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopShippingProfilesTransformer::class)]
final class ShopShippingProfilesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['profile-1'], ['profile-2']];

        $profile1 = self::createStub(ShopShippingProfileInterface::class);
        $profile2 = self::createStub(ShopShippingProfileInterface::class);

        $profileTransformer = self::createStub(ShopShippingProfileTransformerInterface::class);
        $profileTransformer->method('transform')
            ->willReturnMap(
                [
                    [['profile-1'], $profile1],
                    [['profile-2'], $profile2],
                ]
            );

        $transformer = new ShopShippingProfilesTransformer($profileTransformer);

        self::assertSame([$profile1, $profile2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $profileTransformer = self::createStub(ShopShippingProfileTransformerInterface::class);

        $transformer = new ShopShippingProfilesTransformer($profileTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $profileTransformer = self::createStub(ShopShippingProfileTransformerInterface::class);

        $transformer = new ShopShippingProfilesTransformer($profileTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopShippingProfilesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShopShippingProfilesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
