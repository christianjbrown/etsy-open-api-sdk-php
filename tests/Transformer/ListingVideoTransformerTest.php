<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingVideo;
use ChristianBrown\Etsy\Model\ListingVideoInterface;
use ChristianBrown\Etsy\Transformer\ListingVideoTransformer;
use ChristianBrown\Etsy\Transformer\ListingVideoTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingVideo::class)]
#[CoversClass(ListingVideoTransformer::class)]
final class ListingVideoTransformerTest extends TestCase
{
    public function testSetVideoId(): void
    {
        $listingVideo = new ListingVideo(1);

        self::assertSame(2, $listingVideo->setVideoId(2)->getVideoId());
    }

    public function testTransform(): void
    {
        $data = [
            ListingVideoTransformerInterface::KEY_VIDEO_ID => 9000,
            ListingVideoTransformerInterface::KEY_HEIGHT => 101,
            ListingVideoTransformerInterface::KEY_THUMBNAIL_URL => 'v_thumbnailUrl',
            ListingVideoTransformerInterface::KEY_VIDEO_STATE => 'v_videoState',
            ListingVideoTransformerInterface::KEY_VIDEO_URL => 'v_videoUrl',
            ListingVideoTransformerInterface::KEY_WIDTH => 102,
        ];

        $transformer = new ListingVideoTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getVideoId());
        self::assertSame(101, $actual->getHeight());
        self::assertSame('v_thumbnailUrl', $actual->getThumbnailUrl());
        self::assertSame('v_videoState', $actual->getVideoState());
        self::assertSame('v_videoUrl', $actual->getVideoUrl());
        self::assertSame(102, $actual->getWidth());
    }

    /**
     * @param array<string, mixed>                 $data
     * @param Closure(ListingVideoInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ListingVideoTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ListingVideoInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ListingVideoTransformerInterface::KEY_VIDEO_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ListingVideoInterface $m): void {
                self::assertNull($m->getHeight());
                self::assertNull($m->getThumbnailUrl());
                self::assertNull($m->getVideoState());
                self::assertNull($m->getVideoUrl());
                self::assertNull($m->getWidth());
            },
        ];

        yield 'heightWrongType' => [[$id => 1, ListingVideoTransformerInterface::KEY_HEIGHT => 'x'], static function (ListingVideoInterface $m): void {
            self::assertNull($m->getHeight());
        }];
        yield 'thumbnailUrlWrongType' => [[$id => 1, ListingVideoTransformerInterface::KEY_THUMBNAIL_URL => 42], static function (ListingVideoInterface $m): void {
            self::assertNull($m->getThumbnailUrl());
        }];
        yield 'videoStateWrongType' => [[$id => 1, ListingVideoTransformerInterface::KEY_VIDEO_STATE => 42], static function (ListingVideoInterface $m): void {
            self::assertNull($m->getVideoState());
        }];
        yield 'videoUrlWrongType' => [[$id => 1, ListingVideoTransformerInterface::KEY_VIDEO_URL => 42], static function (ListingVideoInterface $m): void {
            self::assertNull($m->getVideoUrl());
        }];
        yield 'widthWrongType' => [[$id => 1, ListingVideoTransformerInterface::KEY_WIDTH => 'x'], static function (ListingVideoInterface $m): void {
            self::assertNull($m->getWidth());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ListingVideoTransformerInterface::KEY_VIDEO_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidVideoId(array $data): void
    {
        $transformer = new ListingVideoTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingVideoTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ListingVideoTransformerInterface::KEY_VIDEO_ID));

        $transformer->transform($data);
    }
}
