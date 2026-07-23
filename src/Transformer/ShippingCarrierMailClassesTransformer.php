<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShippingCarrierMailClassInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ShippingCarrierMailClassesTransformer implements ShippingCarrierMailClassesTransformerInterface
{
    private ShippingCarrierMailClassTransformerInterface $shippingCarrierMailClassTransformer;

    public function __construct(ShippingCarrierMailClassTransformerInterface $shippingCarrierMailClassTransformer)
    {
        $this->shippingCarrierMailClassTransformer = $shippingCarrierMailClassTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShippingCarrierMailClassInterface>
     */
    public function transform(array $data): array
    {
        $mailClasses = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $mailClassData = $values[$i];
            if (!is_array($mailClassData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $mailClasses[] = $this->shippingCarrierMailClassTransformer->transform($mailClassData);
        }

        return $mailClasses;
    }
}
