<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\Review;
use ChristianBrown\Etsy\Model\ReviewInterface;

use function is_int;
use function is_string;

final class ReviewTransformer implements ReviewTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ReviewInterface
    {
        $review = new Review();

        self::applyBuyerUserId($review, $data);
        self::applyCreatedTimestamp($review, $data);
        self::applyCreateTimestamp($review, $data);
        self::applyImageUrlFullxfull($review, $data);
        self::applyLanguage($review, $data);
        self::applyListingId($review, $data);
        self::applyRating($review, $data);
        self::applyReview($review, $data);
        self::applyShopId($review, $data);
        self::applyTransactionId($review, $data);
        self::applyUpdatedTimestamp($review, $data);
        self::applyUpdateTimestamp($review, $data);

        return $review;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBuyerUserId(Review $review, array $data): void
    {
        if (!isset($data[self::KEY_BUYER_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_BUYER_USER_ID])) {
            return;
        }
        $review->setBuyerUserId($data[self::KEY_BUYER_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(Review $review, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $review->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreateTimestamp(Review $review, array $data): void
    {
        if (!isset($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        $review->setCreateTimestamp($data[self::KEY_CREATE_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyImageUrlFullxfull(Review $review, array $data): void
    {
        if (empty($data[self::KEY_IMAGE_URL_FULLXFULL])) {
            return;
        }
        if (!is_string($data[self::KEY_IMAGE_URL_FULLXFULL])) {
            return;
        }
        $review->setImageUrlFullxfull($data[self::KEY_IMAGE_URL_FULLXFULL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLanguage(Review $review, array $data): void
    {
        if (empty($data[self::KEY_LANGUAGE])) {
            return;
        }
        if (!is_string($data[self::KEY_LANGUAGE])) {
            return;
        }
        $review->setLanguage($data[self::KEY_LANGUAGE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyListingId(Review $review, array $data): void
    {
        if (!isset($data[self::KEY_LISTING_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_LISTING_ID])) {
            return;
        }
        $review->setListingId($data[self::KEY_LISTING_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyRating(Review $review, array $data): void
    {
        if (!isset($data[self::KEY_RATING])) {
            return;
        }
        if (!is_int($data[self::KEY_RATING])) {
            return;
        }
        $review->setRating($data[self::KEY_RATING]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReview(Review $review, array $data): void
    {
        if (empty($data[self::KEY_REVIEW])) {
            return;
        }
        if (!is_string($data[self::KEY_REVIEW])) {
            return;
        }
        $review->setReview($data[self::KEY_REVIEW]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopId(Review $review, array $data): void
    {
        if (!isset($data[self::KEY_SHOP_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHOP_ID])) {
            return;
        }
        $review->setShopId($data[self::KEY_SHOP_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTransactionId(Review $review, array $data): void
    {
        if (!isset($data[self::KEY_TRANSACTION_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_TRANSACTION_ID])) {
            return;
        }
        $review->setTransactionId($data[self::KEY_TRANSACTION_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdatedTimestamp(Review $review, array $data): void
    {
        if (!isset($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        $review->setUpdatedTimestamp($data[self::KEY_UPDATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdateTimestamp(Review $review, array $data): void
    {
        if (!isset($data[self::KEY_UPDATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATE_TIMESTAMP])) {
            return;
        }
        $review->setUpdateTimestamp($data[self::KEY_UPDATE_TIMESTAMP]);
    }
}
