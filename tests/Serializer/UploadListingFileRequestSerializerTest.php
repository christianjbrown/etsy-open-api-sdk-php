<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\MultipartFileInterface;
use ChristianBrown\Etsy\Model\UploadListingFileRequest;
use ChristianBrown\Etsy\Serializer\UploadListingFileRequestSerializer;
use ChristianBrown\Etsy\Serializer\UploadListingFileRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UploadListingFileRequest::class)]
#[CoversClass(UploadListingFileRequestSerializer::class)]
final class UploadListingFileRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $file = self::createStub(MultipartFileInterface::class);
        $listingFileId = 1;
        $name = 'test-name';
        $rank = 2;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [2, 'test-int-2'],
                ]
            );

        $uploadListingFileRequest = (new UploadListingFileRequest())
            ->setFile($file)
            ->setListingFileId($listingFileId)
            ->setName($name)
            ->setRank($rank);

        $serializer = new UploadListingFileRequestSerializer($formValueEncoder);

        $expected = [
            UploadListingFileRequestSerializerInterface::KEY_LISTING_FILE_ID => 'test-int-1',
            UploadListingFileRequestSerializerInterface::KEY_NAME => $name,
            UploadListingFileRequestSerializerInterface::KEY_RANK => 'test-int-2',
        ];

        self::assertSame($expected, $serializer->serialize($uploadListingFileRequest));
        self::assertSame($file, $uploadListingFileRequest->getFile());
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);

        $uploadListingFileRequest = new UploadListingFileRequest();

        $serializer = new UploadListingFileRequestSerializer($formValueEncoder);

        self::assertSame([], $serializer->serialize($uploadListingFileRequest));
    }
}
