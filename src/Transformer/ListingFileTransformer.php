<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingFile;
use ChristianBrown\Etsy\Model\ListingFileInterface;

use function is_int;
use function is_string;
use function sprintf;

final class ListingFileTransformer implements ListingFileTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingFileInterface
    {
        if (!isset($data[self::KEY_LISTING_FILE_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_FILE_ID));
        }
        if (!is_int($data[self::KEY_LISTING_FILE_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_FILE_ID));
        }
        $listingFile = new ListingFile($data[self::KEY_LISTING_FILE_ID]);

        self::applyCreatedTimestamp($listingFile, $data);
        self::applyCreateTimestamp($listingFile, $data);
        self::applyFilename($listingFile, $data);
        self::applyFilesize($listingFile, $data);
        self::applyFiletype($listingFile, $data);
        self::applyListingId($listingFile, $data);
        self::applyRank($listingFile, $data);
        self::applySizeBytes($listingFile, $data);

        return $listingFile;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(ListingFile $listingFile, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $listingFile->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreateTimestamp(ListingFile $listingFile, array $data): void
    {
        if (!isset($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        $listingFile->setCreateTimestamp($data[self::KEY_CREATE_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFilename(ListingFile $listingFile, array $data): void
    {
        if (empty($data[self::KEY_FILENAME])) {
            return;
        }
        if (!is_string($data[self::KEY_FILENAME])) {
            return;
        }
        $listingFile->setFilename($data[self::KEY_FILENAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFilesize(ListingFile $listingFile, array $data): void
    {
        if (empty($data[self::KEY_FILESIZE])) {
            return;
        }
        if (!is_string($data[self::KEY_FILESIZE])) {
            return;
        }
        $listingFile->setFilesize($data[self::KEY_FILESIZE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFiletype(ListingFile $listingFile, array $data): void
    {
        if (empty($data[self::KEY_FILETYPE])) {
            return;
        }
        if (!is_string($data[self::KEY_FILETYPE])) {
            return;
        }
        $listingFile->setFiletype($data[self::KEY_FILETYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyListingId(ListingFile $listingFile, array $data): void
    {
        if (!isset($data[self::KEY_LISTING_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_LISTING_ID])) {
            return;
        }
        $listingFile->setListingId($data[self::KEY_LISTING_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyRank(ListingFile $listingFile, array $data): void
    {
        if (!isset($data[self::KEY_RANK])) {
            return;
        }
        if (!is_int($data[self::KEY_RANK])) {
            return;
        }
        $listingFile->setRank($data[self::KEY_RANK]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySizeBytes(ListingFile $listingFile, array $data): void
    {
        if (!isset($data[self::KEY_SIZE_BYTES])) {
            return;
        }
        if (!is_int($data[self::KEY_SIZE_BYTES])) {
            return;
        }
        $listingFile->setSizeBytes($data[self::KEY_SIZE_BYTES]);
    }
}
