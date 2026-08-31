<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UploadListingFileRequestInterface;

interface UploadListingFileRequestSerializerInterface
{
    public const string KEY_LISTING_FILE_ID = 'listing_file_id';
    public const string KEY_NAME = 'name';
    public const string KEY_RANK = 'rank';

    /**
     * @return array<string, string>
     */
    public function serialize(UploadListingFileRequestInterface $uploadListingFileRequest): array;
}
