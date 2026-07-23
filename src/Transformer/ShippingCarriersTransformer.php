<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShippingCarrierInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ShippingCarriersTransformer implements ShippingCarriersTransformerInterface
{
    private ShippingCarrierTransformerInterface $shippingCarrierTransformer;

    public function __construct(ShippingCarrierTransformerInterface $shippingCarrierTransformer)
    {
        $this->shippingCarrierTransformer = $shippingCarrierTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShippingCarrierInterface>
     */
    public function transform(array $data): array
    {
        $shippingCarriers = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $shippingCarrierData = $values[$i];
            if (!is_array($shippingCarrierData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $shippingCarriers[] = $this->shippingCarrierTransformer->transform($shippingCarrierData);
        }

        return $shippingCarriers;
    }
}
