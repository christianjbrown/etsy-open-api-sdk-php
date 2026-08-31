<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Http;

use ChristianBrown\Etsy\Http\MultipartFormDataBuilder;
use ChristianBrown\Etsy\Http\MultipartFormDataBuilderInterface;
use ChristianBrown\Etsy\Model\MultipartFile;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function mb_strlen;
use function sprintf;

#[CoversClass(MultipartFile::class)]
#[CoversClass(MultipartFormDataBuilder::class)]
final class MultipartFormDataBuilderTest extends TestCase
{
    public function testBuildWithFieldsAndFile(): void
    {
        $builder = new MultipartFormDataBuilder();
        $file = (new MultipartFile('placeholder-field', 'placeholder.bin', 'application/octet-stream', 'placeholder-bytes'))
            ->setFieldName('image')
            ->setFileName('photo.jpg')
            ->setContentType('image/jpeg')
            ->setContents('binary-bytes');

        $expected = "--test-boundary\r\n"
        ."Content-Disposition: form-data; name=\"rank\"\r\n"
        ."\r\n"
        ."1\r\n"
        ."--test-boundary\r\n"
        ."Content-Disposition: form-data; name=\"image\"; filename=\"photo.jpg\"\r\n"
        ."Content-Type: image/jpeg\r\n"
        ."\r\n"
        ."binary-bytes\r\n"
        ."--test-boundary--\r\n";

        self::assertSame($expected, $builder->build('test-boundary', ['rank' => '1'], $file));
    }

    public function testBuildWithoutFile(): void
    {
        $builder = new MultipartFormDataBuilder();

        $expected = "--test-boundary\r\n"
        ."Content-Disposition: form-data; name=\"listing_image_id\"\r\n"
        ."\r\n"
        ."42\r\n"
        ."--test-boundary--\r\n";

        self::assertSame($expected, $builder->build('test-boundary', ['listing_image_id' => '42']));
    }

    public function testGenerateBoundaryIsPrefixedAndUnique(): void
    {
        $builder = new MultipartFormDataBuilder();

        $boundary = $builder->generateBoundary();

        self::assertStringStartsWith(sprintf(MultipartFormDataBuilderInterface::BOUNDARY_SPRINTF, ''), $boundary);
        self::assertSame(mb_strlen(sprintf(MultipartFormDataBuilderInterface::BOUNDARY_SPRINTF, '')) + (MultipartFormDataBuilderInterface::BOUNDARY_BYTES * 2), mb_strlen($boundary));
        self::assertNotSame($boundary, $builder->generateBoundary());
    }

    public function testToContentTypeHeaderValue(): void
    {
        $builder = new MultipartFormDataBuilder();

        self::assertSame('multipart/form-data; boundary=test-boundary', $builder->toContentTypeHeaderValue('test-boundary'));
    }
}
