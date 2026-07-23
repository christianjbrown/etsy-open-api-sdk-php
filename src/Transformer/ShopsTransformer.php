<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopInterface;

use function array_values;
use function count;
use function sprintf;

final class ShopsTransformer implements ShopsTransformerInterface
{
    private ShopTransformerInterface $shopTransformer;

    public function __construct(ShopTransformerInterface $shopTransformer)
    {
        $this->shopTransformer = $shopTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopInterface>
     */
    public function transform(array $data): array
    {
        $shops = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $shopData = $values[$i];
            if (!is_array($shopData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $shops[] = $this->shopTransformer->transform($shopData);
        }

        return $shops;
    }
}
