<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingFileInterface;

interface ListingFileTransformerInterface
{
    public const string KEY_CREATE_TIMESTAMP = 'create_timestamp';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_FILENAME = 'filename';
    public const string KEY_FILESIZE = 'filesize';
    public const string KEY_FILETYPE = 'filetype';
    public const string KEY_LISTING_FILE_ID = 'listing_file_id';
    public const string KEY_LISTING_ID = 'listing_id';
    public const string KEY_RANK = 'rank';
    public const string KEY_SIZE_BYTES = 'size_bytes';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingFileInterface;
}
