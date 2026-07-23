<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ReviewInterface;

interface ReviewTransformerInterface
{
    public const string KEY_BUYER_USER_ID = 'buyer_user_id';
    public const string KEY_CREATE_TIMESTAMP = 'create_timestamp';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_IMAGE_URL_FULLXFULL = 'image_url_fullxfull';
    public const string KEY_LANGUAGE = 'language';
    public const string KEY_LISTING_ID = 'listing_id';
    public const string KEY_RATING = 'rating';
    public const string KEY_REVIEW = 'review';
    public const string KEY_SHOP_ID = 'shop_id';
    public const string KEY_TRANSACTION_ID = 'transaction_id';
    public const string KEY_UPDATE_TIMESTAMP = 'update_timestamp';
    public const string KEY_UPDATED_TIMESTAMP = 'updated_timestamp';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ReviewInterface;
}
