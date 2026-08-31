<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\MultipartFileInterface;
use ChristianBrown\Etsy\Model\UploadListingVideoRequest;
use ChristianBrown\Etsy\Serializer\UploadListingVideoRequestSerializer;
use ChristianBrown\Etsy\Serializer\UploadListingVideoRequestSerializerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UploadListingVideoRequest::class)]
#[CoversClass(UploadListingVideoRequestSerializer::class)]
final class UploadListingVideoRequestSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $name = 'test-name';
        $video = self::createStub(MultipartFileInterface::class);
        $videoId = 1;

        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);
        $formValueEncoder->method('encodeInt')
            ->willReturnMap(
                [
                    [1, 'test-int-1'],
                ]
            );

        $uploadListingVideoRequest = (new UploadListingVideoRequest())
            ->setName($name)
            ->setVideo($video)
            ->setVideoId($videoId);

        $serializer = new UploadListingVideoRequestSerializer($formValueEncoder);

        $expected = [
            UploadListingVideoRequestSerializerInterface::KEY_NAME => $name,
            UploadListingVideoRequestSerializerInterface::KEY_VIDEO_ID => 'test-int-1',
        ];

        self::assertSame($expected, $serializer->serialize($uploadListingVideoRequest));
        self::assertSame($video, $uploadListingVideoRequest->getVideo());
    }

    public function testSerializeWithoutOptionalFields(): void
    {
        $formValueEncoder = self::createStub(FormValueEncoderInterface::class);

        $uploadListingVideoRequest = new UploadListingVideoRequest();

        $serializer = new UploadListingVideoRequestSerializer($formValueEncoder);

        self::assertSame([], $serializer->serialize($uploadListingVideoRequest));
    }
}
