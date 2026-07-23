<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShippingCarrierMailClassInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarrierMailClassesTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarrierMailClassesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarrierMailClassTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShippingCarrierMailClassesTransformer::class)]
final class ShippingCarrierMailClassesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['class-1'], ['class-2']];

        $class1 = self::createStub(ShippingCarrierMailClassInterface::class);
        $class2 = self::createStub(ShippingCarrierMailClassInterface::class);

        $mailClassTransformer = self::createStub(ShippingCarrierMailClassTransformerInterface::class);
        $mailClassTransformer->method('transform')
            ->willReturnMap(
                [
                    [['class-1'], $class1],
                    [['class-2'], $class2],
                ]
            );

        $transformer = new ShippingCarrierMailClassesTransformer($mailClassTransformer);

        self::assertSame([$class1, $class2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $mailClassTransformer = self::createStub(ShippingCarrierMailClassTransformerInterface::class);

        $transformer = new ShippingCarrierMailClassesTransformer($mailClassTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $mailClassTransformer = self::createStub(ShippingCarrierMailClassTransformerInterface::class);

        $transformer = new ShippingCarrierMailClassesTransformer($mailClassTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingCarrierMailClassesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShippingCarrierMailClassesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
