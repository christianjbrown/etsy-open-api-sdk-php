<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\UserAddressInterface;

interface UserAddressTransformerInterface
{
    public const string KEY_CITY = 'city';
    public const string KEY_COUNTRY_NAME = 'country_name';
    public const string KEY_FIRST_LINE = 'first_line';
    public const string KEY_IS_DEFAULT_SHIPPING_ADDRESS = 'is_default_shipping_address';
    public const string KEY_ISO_COUNTRY_CODE = 'iso_country_code';
    public const string KEY_NAME = 'name';
    public const string KEY_SECOND_LINE = 'second_line';
    public const string KEY_STATE = 'state';
    public const string KEY_USER_ADDRESS_ID = 'user_address_id';
    public const string KEY_USER_ID = 'user_id';
    public const string KEY_ZIP = 'zip';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): UserAddressInterface;
}
