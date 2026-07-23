<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopSectionInterface;
use ChristianBrown\Etsy\Transformer\ShopSectionsTransformer;
use ChristianBrown\Etsy\Transformer\ShopSectionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopSectionTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopSectionsTransformer::class)]
final class ShopSectionsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['section-1'], ['section-2']];

        $section1 = self::createStub(ShopSectionInterface::class);
        $section2 = self::createStub(ShopSectionInterface::class);

        $shopSectionTransformer = self::createStub(ShopSectionTransformerInterface::class);
        $shopSectionTransformer->method('transform')
            ->willReturnMap(
                [
                    [['section-1'], $section1],
                    [['section-2'], $section2],
                ]
            );

        $transformer = new ShopSectionsTransformer($shopSectionTransformer);

        self::assertSame([$section1, $section2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $shopSectionTransformer = self::createStub(ShopSectionTransformerInterface::class);

        $transformer = new ShopSectionsTransformer($shopSectionTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $shopSectionTransformer = self::createStub(ShopSectionTransformerInterface::class);

        $transformer = new ShopSectionsTransformer($shopSectionTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopSectionsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShopSectionsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
