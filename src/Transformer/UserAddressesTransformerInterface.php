<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\UserAddressInterface;

interface UserAddressesTransformerInterface
{
    public const string ARRAY_NAME = 'user_address';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, UserAddressInterface>
     */
    public function transform(array $data): array;
}
