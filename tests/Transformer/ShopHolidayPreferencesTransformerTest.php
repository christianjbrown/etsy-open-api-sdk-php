<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferencesTransformer;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferencesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferenceTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopHolidayPreferencesTransformer::class)]
final class ShopHolidayPreferencesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['preference-1'], ['preference-2']];

        $preference1 = self::createStub(ShopHolidayPreferenceInterface::class);
        $preference2 = self::createStub(ShopHolidayPreferenceInterface::class);

        $shopHolidayPreferenceTransformer = self::createStub(ShopHolidayPreferenceTransformerInterface::class);
        $shopHolidayPreferenceTransformer->method('transform')
            ->willReturnMap(
                [
                    [['preference-1'], $preference1],
                    [['preference-2'], $preference2],
                ]
            );

        $transformer = new ShopHolidayPreferencesTransformer($shopHolidayPreferenceTransformer);

        self::assertSame([$preference1, $preference2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $shopHolidayPreferenceTransformer = self::createStub(ShopHolidayPreferenceTransformerInterface::class);

        $transformer = new ShopHolidayPreferencesTransformer($shopHolidayPreferenceTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $shopHolidayPreferenceTransformer = self::createStub(ShopHolidayPreferenceTransformerInterface::class);

        $transformer = new ShopHolidayPreferencesTransformer($shopHolidayPreferenceTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopHolidayPreferencesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShopHolidayPreferencesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
