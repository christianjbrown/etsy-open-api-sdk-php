<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingVideoInterface;

interface ListingVideoTransformerInterface
{
    public const string KEY_HEIGHT = 'height';
    public const string KEY_THUMBNAIL_URL = 'thumbnail_url';
    public const string KEY_VIDEO_ID = 'video_id';
    public const string KEY_VIDEO_STATE = 'video_state';
    public const string KEY_VIDEO_URL = 'video_url';
    public const string KEY_WIDTH = 'width';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingVideoInterface;
}
