<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShipmentInterface;

use function array_values;
use function count;
use function sprintf;

final class ShipmentsTransformer implements ShipmentsTransformerInterface
{
    private ShipmentTransformerInterface $shipmentTransformer;

    public function __construct(ShipmentTransformerInterface $shipmentTransformer)
    {
        $this->shipmentTransformer = $shipmentTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShipmentInterface>
     */
    public function transform(array $data): array
    {
        $shipments = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $shipmentData = $values[$i];
            if (!is_array($shipmentData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $shipments[] = $this->shipmentTransformer->transform($shipmentData);
        }

        return $shipments;
    }
}
