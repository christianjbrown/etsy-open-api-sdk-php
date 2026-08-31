<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\MultipartFileInterface;
use ChristianBrown\Etsy\Model\UploadListingImageRequest;
use ChristianBrown\Etsy\Serializer\UploadListingImageRequestSerializer;
use ChristianBrown\Etsy\Serializer\UploadListingImageRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UploadListingImageRequest::class)]
#[CoversClass(UploadListingImageRequestSerializer::class)]
final class UploadListingImageRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $altText = 'test-altText';
        $image = self::createStub(MultipartFileInterface::class);
        $isWatermarked = true;
        $listingImageId = 1;
        $overwrite = true;
        $rank = 2;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                    [2, 'test-int-2'],
                ]
            );
        $formValueEncoder->method('encodeBool')
            ->willReturnMap(
                [
                    [true, 'test-bool-true'],
                    [false, 'test-bool-false'],
                ]
            );

        $uploadListingImageRequest = (new UploadListingImageRequest())
            ->setAltText($altText)
            ->setImage($image)
            ->setIsWatermarked($isWatermarked)
            ->setListingImageId($listingImageId)
            ->setOverwrite($overwrite)
            ->setRank($rank);

        $serializer = new UploadListingImageRequestSerializer($formValueEncoder);

        $expected = [
            UploadListingImageRequestSerializerInterface::KEY_ALT_TEXT => $altText,
            UploadListingImageRequestSerializerInterface::KEY_IS_WATERMARKED => 'test-bool-true',
            UploadListingImageRequestSerializerInterface::KEY_LISTING_IMAGE_ID => 'test-int-1',
            UploadListingImageRequestSerializerInterface::KEY_OVERWRITE => 'test-bool-true',
            UploadListingImageRequestSerializerInterface::KEY_RANK => 'test-int-2',
        ];

        self::assertSame($expected, $serializer->serialize($uploadListingImageRequest));
        self::assertSame($image, $uploadListingImageRequest->getImage());
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);

        $uploadListingImageRequest = new UploadListingImageRequest();

        $serializer = new UploadListingImageRequestSerializer($formValueEncoder);

        self::assertSame([], $serializer->serialize($uploadListingImageRequest));
    }
}
