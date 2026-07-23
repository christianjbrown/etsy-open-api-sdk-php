<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingFile;
use ChristianBrown\Etsy\Model\ListingFileInterface;
use ChristianBrown\Etsy\Transformer\ListingFileTransformer;
use ChristianBrown\Etsy\Transformer\ListingFileTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingFile::class)]
#[CoversClass(ListingFileTransformer::class)]
final class ListingFileTransformerTest extends TestCase
{
    public function testSetListingFileId(): void
    {
        $listingFile = new ListingFile(1);

        self::assertSame(2, $listingFile->setListingFileId(2)->getListingFileId());
    }

    public function testTransform(): void
    {
        $data = [
            ListingFileTransformerInterface::KEY_LISTING_FILE_ID => 9000,
            ListingFileTransformerInterface::KEY_LISTING_ID => 101,
            ListingFileTransformerInterface::KEY_RANK => 102,
            ListingFileTransformerInterface::KEY_FILENAME => 'v_filename',
            ListingFileTransformerInterface::KEY_FILESIZE => 'v_filesize',
            ListingFileTransformerInterface::KEY_FILETYPE => 'v_filetype',
            ListingFileTransformerInterface::KEY_SIZE_BYTES => 103,
            ListingFileTransformerInterface::KEY_CREATE_TIMESTAMP => 104,
            ListingFileTransformerInterface::KEY_CREATED_TIMESTAMP => 105,
        ];

        $transformer = new ListingFileTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getListingFileId());
        self::assertSame(101, $actual->getListingId());
        self::assertSame(102, $actual->getRank());
        self::assertSame('v_filename', $actual->getFilename());
        self::assertSame('v_filesize', $actual->getFilesize());
        self::assertSame('v_filetype', $actual->getFiletype());
        self::assertSame(103, $actual->getSizeBytes());
        self::assertSame(104, $actual->getCreateTimestamp());
        self::assertSame(105, $actual->getCreatedTimestamp());
    }

    /**
     * @param array<string, mixed>                $data
     * @param Closure(ListingFileInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ListingFileTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ListingFileInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ListingFileTransformerInterface::KEY_LISTING_FILE_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ListingFileInterface $m): void {
                self::assertNull($m->getListingId());
                self::assertNull($m->getRank());
                self::assertNull($m->getFilename());
                self::assertNull($m->getFilesize());
                self::assertNull($m->getFiletype());
                self::assertNull($m->getSizeBytes());
                self::assertNull($m->getCreateTimestamp());
                self::assertNull($m->getCreatedTimestamp());
            },
        ];

        yield 'listingIdWrongType' => [[$id => 1, ListingFileTransformerInterface::KEY_LISTING_ID => 'x'], static function (ListingFileInterface $m): void {
            self::assertNull($m->getListingId());
        }];
        yield 'rankWrongType' => [[$id => 1, ListingFileTransformerInterface::KEY_RANK => 'x'], static function (ListingFileInterface $m): void {
            self::assertNull($m->getRank());
        }];
        yield 'filenameWrongType' => [[$id => 1, ListingFileTransformerInterface::KEY_FILENAME => 42], static function (ListingFileInterface $m): void {
            self::assertNull($m->getFilename());
        }];
        yield 'filesizeWrongType' => [[$id => 1, ListingFileTransformerInterface::KEY_FILESIZE => 42], static function (ListingFileInterface $m): void {
            self::assertNull($m->getFilesize());
        }];
        yield 'filetypeWrongType' => [[$id => 1, ListingFileTransformerInterface::KEY_FILETYPE => 42], static function (ListingFileInterface $m): void {
            self::assertNull($m->getFiletype());
        }];
        yield 'sizeBytesWrongType' => [[$id => 1, ListingFileTransformerInterface::KEY_SIZE_BYTES => 'x'], static function (ListingFileInterface $m): void {
            self::assertNull($m->getSizeBytes());
        }];
        yield 'createTimestampWrongType' => [[$id => 1, ListingFileTransformerInterface::KEY_CREATE_TIMESTAMP => 'x'], static function (ListingFileInterface $m): void {
            self::assertNull($m->getCreateTimestamp());
        }];
        yield 'createdTimestampWrongType' => [[$id => 1, ListingFileTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (ListingFileInterface $m): void {
            self::assertNull($m->getCreatedTimestamp());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ListingFileTransformerInterface::KEY_LISTING_FILE_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidListingFileId(array $data): void
    {
        $transformer = new ListingFileTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingFileTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ListingFileTransformerInterface::KEY_LISTING_FILE_ID));

        $transformer->transform($data);
    }
}
