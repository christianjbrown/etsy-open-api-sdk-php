<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingVideo;
use ChristianBrown\Etsy\Model\ListingVideoInterface;

use function is_int;
use function is_string;
use function sprintf;

final class ListingVideoTransformer implements ListingVideoTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingVideoInterface
    {
        if (!isset($data[self::KEY_VIDEO_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_VIDEO_ID));
        }
        if (!is_int($data[self::KEY_VIDEO_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_VIDEO_ID));
        }
        $listingVideo = new ListingVideo($data[self::KEY_VIDEO_ID]);

        self::applyHeight($listingVideo, $data);
        self::applyThumbnailUrl($listingVideo, $data);
        self::applyVideoState($listingVideo, $data);
        self::applyVideoUrl($listingVideo, $data);
        self::applyWidth($listingVideo, $data);

        return $listingVideo;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyHeight(ListingVideo $listingVideo, array $data): void
    {
        if (!isset($data[self::KEY_HEIGHT])) {
            return;
        }
        if (!is_int($data[self::KEY_HEIGHT])) {
            return;
        }
        $listingVideo->setHeight($data[self::KEY_HEIGHT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyThumbnailUrl(ListingVideo $listingVideo, array $data): void
    {
        if (empty($data[self::KEY_THUMBNAIL_URL])) {
            return;
        }
        if (!is_string($data[self::KEY_THUMBNAIL_URL])) {
            return;
        }
        $listingVideo->setThumbnailUrl($data[self::KEY_THUMBNAIL_URL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyVideoState(ListingVideo $listingVideo, array $data): void
    {
        if (empty($data[self::KEY_VIDEO_STATE])) {
            return;
        }
        if (!is_string($data[self::KEY_VIDEO_STATE])) {
            return;
        }
        $listingVideo->setVideoState($data[self::KEY_VIDEO_STATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyVideoUrl(ListingVideo $listingVideo, array $data): void
    {
        if (empty($data[self::KEY_VIDEO_URL])) {
            return;
        }
        if (!is_string($data[self::KEY_VIDEO_URL])) {
            return;
        }
        $listingVideo->setVideoUrl($data[self::KEY_VIDEO_URL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyWidth(ListingVideo $listingVideo, array $data): void
    {
        if (!isset($data[self::KEY_WIDTH])) {
            return;
        }
        if (!is_int($data[self::KEY_WIDTH])) {
            return;
        }
        $listingVideo->setWidth($data[self::KEY_WIDTH]);
    }
}
