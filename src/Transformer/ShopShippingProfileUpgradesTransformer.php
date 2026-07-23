<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ShopShippingProfileUpgradesTransformer implements ShopShippingProfileUpgradesTransformerInterface
{
    private ShopShippingProfileUpgradeTransformerInterface $shopShippingProfileUpgradeTransformer;

    public function __construct(ShopShippingProfileUpgradeTransformerInterface $shopShippingProfileUpgradeTransformer)
    {
        $this->shopShippingProfileUpgradeTransformer = $shopShippingProfileUpgradeTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopShippingProfileUpgradeInterface>
     */
    public function transform(array $data): array
    {
        $upgrades = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $upgradeData = $values[$i];
            if (!is_array($upgradeData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $upgrades[] = $this->shopShippingProfileUpgradeTransformer->transform($upgradeData);
        }

        return $upgrades;
    }
}
