<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopProductionPartnerInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ShopProductionPartnersTransformer implements ShopProductionPartnersTransformerInterface
{
    private ShopProductionPartnerTransformerInterface $shopProductionPartnerTransformer;

    public function __construct(ShopProductionPartnerTransformerInterface $shopProductionPartnerTransformer)
    {
        $this->shopProductionPartnerTransformer = $shopProductionPartnerTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopProductionPartnerInterface>
     */
    public function transform(array $data): array
    {
        $shopProductionPartners = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $shopProductionPartnerData = $values[$i];
            if (!is_array($shopProductionPartnerData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $shopProductionPartners[] = $this->shopProductionPartnerTransformer->transform($shopProductionPartnerData);
        }

        return $shopProductionPartners;
    }
}
