<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UploadListingImageRequestInterface;

interface UploadListingImageRequestSerializerInterface
{
    public const string KEY_ALT_TEXT = 'alt_text';
    public const string KEY_IS_WATERMARKED = 'is_watermarked';
    public const string KEY_LISTING_IMAGE_ID = 'listing_image_id';
    public const string KEY_OVERWRITE = 'overwrite';
    public const string KEY_RANK = 'rank';

    /**
     * @return array<string, string>
     */
    public function serialize(UploadListingImageRequestInterface $uploadListingImageRequest): array;
}
