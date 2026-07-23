<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ShopShippingProfilesTransformer implements ShopShippingProfilesTransformerInterface
{
    private ShopShippingProfileTransformerInterface $shopShippingProfileTransformer;

    public function __construct(ShopShippingProfileTransformerInterface $shopShippingProfileTransformer)
    {
        $this->shopShippingProfileTransformer = $shopShippingProfileTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopShippingProfileInterface>
     */
    public function transform(array $data): array
    {
        $shippingProfiles = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $shippingProfileData = $values[$i];
            if (!is_array($shippingProfileData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $shippingProfiles[] = $this->shopShippingProfileTransformer->transform($shippingProfileData);
        }

        return $shippingProfiles;
    }
}
