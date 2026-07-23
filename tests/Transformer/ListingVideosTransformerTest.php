<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingVideoInterface;
use ChristianBrown\Etsy\Transformer\ListingVideosTransformer;
use ChristianBrown\Etsy\Transformer\ListingVideosTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingVideoTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingVideosTransformer::class)]
final class ListingVideosTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-video-1'], ['test-video-2']];

        $video1 = self::createStub(ListingVideoInterface::class);
        $video2 = self::createStub(ListingVideoInterface::class);

        $videoTransformer = self::createStub(ListingVideoTransformerInterface::class);
        $videoTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-video-1'], $video1],
                    [['test-video-2'], $video2],
                ]
            );

        $transformer = new ListingVideosTransformer($videoTransformer);

        self::assertSame([$video1, $video2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $videoTransformer = self::createStub(ListingVideoTransformerInterface::class);

        $transformer = new ListingVideosTransformer($videoTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $videoTransformer = self::createStub(ListingVideoTransformerInterface::class);

        $transformer = new ListingVideosTransformer($videoTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVideosTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ListingVideosTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
