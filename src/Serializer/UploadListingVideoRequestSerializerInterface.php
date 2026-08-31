<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UploadListingVideoRequestInterface;

interface UploadListingVideoRequestSerializerInterface
{
    public const string KEY_NAME = 'name';
    public const string KEY_VIDEO_ID = 'video_id';

    /**
     * @return array<string, string>
     */
    public function serialize(UploadListingVideoRequestInterface $uploadListingVideoRequest): array;
}
