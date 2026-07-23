<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ReviewInterface;
use ChristianBrown\Etsy\Transformer\ReviewsTransformer;
use ChristianBrown\Etsy\Transformer\ReviewsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ReviewTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ReviewsTransformer::class)]
final class ReviewsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['review-1'], ['review-2']];

        $review1 = self::createStub(ReviewInterface::class);
        $review2 = self::createStub(ReviewInterface::class);

        $reviewTransformer = self::createStub(ReviewTransformerInterface::class);
        $reviewTransformer->method('transform')
            ->willReturnMap(
                [
                    [['review-1'], $review1],
                    [['review-2'], $review2],
                ]
            );

        $transformer = new ReviewsTransformer($reviewTransformer);

        self::assertSame([$review1, $review2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $reviewTransformer = self::createStub(ReviewTransformerInterface::class);

        $transformer = new ReviewsTransformer($reviewTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $reviewTransformer = self::createStub(ReviewTransformerInterface::class);

        $transformer = new ReviewsTransformer($reviewTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReviewsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ReviewsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
