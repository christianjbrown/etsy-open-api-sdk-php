<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingFileInterface;
use ChristianBrown\Etsy\Transformer\ListingFilesTransformer;
use ChristianBrown\Etsy\Transformer\ListingFilesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingFileTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingFilesTransformer::class)]
final class ListingFilesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-file-1'], ['test-file-2']];

        $file1 = self::createStub(ListingFileInterface::class);
        $file2 = self::createStub(ListingFileInterface::class);

        $fileTransformer = self::createStub(ListingFileTransformerInterface::class);
        $fileTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-file-1'], $file1],
                    [['test-file-2'], $file2],
                ]
            );

        $transformer = new ListingFilesTransformer($fileTransformer);

        self::assertSame([$file1, $file2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $fileTransformer = self::createStub(ListingFileTransformerInterface::class);

        $transformer = new ListingFilesTransformer($fileTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $fileTransformer = self::createStub(ListingFileTransformerInterface::class);

        $transformer = new ListingFilesTransformer($fileTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingFilesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ListingFilesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
