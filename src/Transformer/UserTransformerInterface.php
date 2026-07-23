<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\UserInterface;

interface UserTransformerInterface
{
    public const string KEY_FIRST_NAME = 'first_name';
    public const string KEY_IMAGE_URL_75X75 = 'image_url_75x75';
    public const string KEY_LAST_NAME = 'last_name';
    public const string KEY_PRIMARY_EMAIL = 'primary_email';
    public const string KEY_USER_ID = 'user_id';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): UserInterface;
}
