<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\UserAddressInterface;

use function array_values;
use function count;
use function sprintf;

final class UserAddressesTransformer implements UserAddressesTransformerInterface
{
    private UserAddressTransformerInterface $userAddressTransformer;

    public function __construct(UserAddressTransformerInterface $userAddressTransformer)
    {
        $this->userAddressTransformer = $userAddressTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, UserAddressInterface>
     */
    public function transform(array $data): array
    {
        $userAddresses = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $userAddressData = $values[$i];
            if (!is_array($userAddressData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $userAddresses[] = $this->userAddressTransformer->transform($userAddressData);
        }

        return $userAddresses;
    }
}
