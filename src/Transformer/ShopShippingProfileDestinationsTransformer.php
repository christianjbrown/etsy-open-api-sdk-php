<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ShopShippingProfileDestinationsTransformer implements ShopShippingProfileDestinationsTransformerInterface
{
    private ShopShippingProfileDestinationTransformerInterface $shopShippingProfileDestinationTransformer;

    public function __construct(ShopShippingProfileDestinationTransformerInterface $shopShippingProfileDestinationTransformer)
    {
        $this->shopShippingProfileDestinationTransformer = $shopShippingProfileDestinationTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopShippingProfileDestinationInterface>
     */
    public function transform(array $data): array
    {
        $destinations = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $destinationData = $values[$i];
            if (!is_array($destinationData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $destinations[] = $this->shopShippingProfileDestinationTransformer->transform($destinationData);
        }

        return $destinations;
    }
}
