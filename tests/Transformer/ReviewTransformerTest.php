<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\Review;
use ChristianBrown\Etsy\Model\ReviewInterface;
use ChristianBrown\Etsy\Transformer\ReviewTransformer;
use ChristianBrown\Etsy\Transformer\ReviewTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Review::class)]
#[CoversClass(ReviewTransformer::class)]
final class ReviewTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            ReviewTransformerInterface::KEY_BUYER_USER_ID => 111,
            ReviewTransformerInterface::KEY_CREATED_TIMESTAMP => 1600000001,
            ReviewTransformerInterface::KEY_CREATE_TIMESTAMP => 1600000002,
            ReviewTransformerInterface::KEY_IMAGE_URL_FULLXFULL => 'https://img',
            ReviewTransformerInterface::KEY_LANGUAGE => 'en',
            ReviewTransformerInterface::KEY_LISTING_ID => 222,
            ReviewTransformerInterface::KEY_RATING => 5,
            ReviewTransformerInterface::KEY_REVIEW => 'Great!',
            ReviewTransformerInterface::KEY_SHOP_ID => 333,
            ReviewTransformerInterface::KEY_TRANSACTION_ID => 444,
            ReviewTransformerInterface::KEY_UPDATED_TIMESTAMP => 1600000003,
            ReviewTransformerInterface::KEY_UPDATE_TIMESTAMP => 1600000004,
        ];

        $transformer = new ReviewTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(111, $actual->getBuyerUserId());
        self::assertSame(1600000001, $actual->getCreatedTimestamp());
        self::assertSame(1600000002, $actual->getCreateTimestamp());
        self::assertSame('https://img', $actual->getImageUrlFullxfull());
        self::assertSame('en', $actual->getLanguage());
        self::assertSame(222, $actual->getListingId());
        self::assertSame(5, $actual->getRating());
        self::assertSame('Great!', $actual->getReview());
        self::assertSame(333, $actual->getShopId());
        self::assertSame(444, $actual->getTransactionId());
        self::assertSame(1600000003, $actual->getUpdatedTimestamp());
        self::assertSame(1600000004, $actual->getUpdateTimestamp());
    }

    /**
     * @param array<string, mixed>           $data
     * @param Closure(ReviewInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ReviewTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ReviewInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        yield 'allAbsent' => [
            [],
            static function (ReviewInterface $model): void {
                self::assertNull($model->getBuyerUserId());
                self::assertNull($model->getCreatedTimestamp());
                self::assertNull($model->getCreateTimestamp());
                self::assertNull($model->getImageUrlFullxfull());
                self::assertNull($model->getLanguage());
                self::assertNull($model->getListingId());
                self::assertNull($model->getRating());
                self::assertNull($model->getReview());
                self::assertNull($model->getShopId());
                self::assertNull($model->getTransactionId());
                self::assertNull($model->getUpdatedTimestamp());
                self::assertNull($model->getUpdateTimestamp());
            },
        ];

        yield 'buyerUserIdWrongType' => [[ReviewTransformerInterface::KEY_BUYER_USER_ID => 'x'], static function (ReviewInterface $m): void {
            self::assertNull($m->getBuyerUserId());
        }];
        yield 'createdTimestampWrongType' => [[ReviewTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (ReviewInterface $m): void {
            self::assertNull($m->getCreatedTimestamp());
        }];
        yield 'createTimestampWrongType' => [[ReviewTransformerInterface::KEY_CREATE_TIMESTAMP => 'x'], static function (ReviewInterface $m): void {
            self::assertNull($m->getCreateTimestamp());
        }];
        yield 'imageUrlFullxfullWrongType' => [[ReviewTransformerInterface::KEY_IMAGE_URL_FULLXFULL => 42], static function (ReviewInterface $m): void {
            self::assertNull($m->getImageUrlFullxfull());
        }];
        yield 'languageWrongType' => [[ReviewTransformerInterface::KEY_LANGUAGE => 42], static function (ReviewInterface $m): void {
            self::assertNull($m->getLanguage());
        }];
        yield 'listingIdWrongType' => [[ReviewTransformerInterface::KEY_LISTING_ID => 'x'], static function (ReviewInterface $m): void {
            self::assertNull($m->getListingId());
        }];
        yield 'ratingWrongType' => [[ReviewTransformerInterface::KEY_RATING => 'x'], static function (ReviewInterface $m): void {
            self::assertNull($m->getRating());
        }];
        yield 'reviewWrongType' => [[ReviewTransformerInterface::KEY_REVIEW => 42], static function (ReviewInterface $m): void {
            self::assertNull($m->getReview());
        }];
        yield 'shopIdWrongType' => [[ReviewTransformerInterface::KEY_SHOP_ID => 'x'], static function (ReviewInterface $m): void {
            self::assertNull($m->getShopId());
        }];
        yield 'transactionIdWrongType' => [[ReviewTransformerInterface::KEY_TRANSACTION_ID => 'x'], static function (ReviewInterface $m): void {
            self::assertNull($m->getTransactionId());
        }];
        yield 'updatedTimestampWrongType' => [[ReviewTransformerInterface::KEY_UPDATED_TIMESTAMP => 'x'], static function (ReviewInterface $m): void {
            self::assertNull($m->getUpdatedTimestamp());
        }];
        yield 'updateTimestampWrongType' => [[ReviewTransformerInterface::KEY_UPDATE_TIMESTAMP => 'x'], static function (ReviewInterface $m): void {
            self::assertNull($m->getUpdateTimestamp());
        }];
    }
}
