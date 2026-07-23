<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingImageInterface;

interface ListingImageTransformerInterface
{
    public const string KEY_ALT_TEXT = 'alt_text';
    public const string KEY_BLUE = 'blue';
    public const string KEY_BRIGHTNESS = 'brightness';
    public const string KEY_CREATED_TIMESTAMP = 'created_timestamp';
    public const string KEY_CREATION_TSZ = 'creation_tsz';
    public const string KEY_FULL_HEIGHT = 'full_height';
    public const string KEY_FULL_WIDTH = 'full_width';
    public const string KEY_GREEN = 'green';
    public const string KEY_HEX_CODE = 'hex_code';
    public const string KEY_HUE = 'hue';
    public const string KEY_IS_BLACK_AND_WHITE = 'is_black_and_white';
    public const string KEY_LISTING_ID = 'listing_id';
    public const string KEY_LISTING_IMAGE_ID = 'listing_image_id';
    public const string KEY_RANK = 'rank';
    public const string KEY_RED = 'red';
    public const string KEY_SATURATION = 'saturation';
    public const string KEY_URL_170X135 = 'url_170x135';
    public const string KEY_URL_570XN = 'url_570xN';
    public const string KEY_URL_75X75 = 'url_75x75';
    public const string KEY_URL_FULLXFULL = 'url_fullxfull';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingImageInterface;
}
