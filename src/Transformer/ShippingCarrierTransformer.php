<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShippingCarrier;
use ChristianBrown\Etsy\Model\ShippingCarrierInterface;

use function is_array;
use function is_int;
use function is_string;
use function sprintf;

final class ShippingCarrierTransformer implements ShippingCarrierTransformerInterface
{
    private ShippingCarrierMailClassesTransformerInterface $shippingCarrierMailClassesTransformer;

    public function __construct(ShippingCarrierMailClassesTransformerInterface $shippingCarrierMailClassesTransformer)
    {
        $this->shippingCarrierMailClassesTransformer = $shippingCarrierMailClassesTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShippingCarrierInterface
    {
        if (!isset($data[self::KEY_SHIPPING_CARRIER_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_SHIPPING_CARRIER_ID));
        }
        if (!is_int($data[self::KEY_SHIPPING_CARRIER_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_SHIPPING_CARRIER_ID));
        }
        $shippingCarrier = new ShippingCarrier($data[self::KEY_SHIPPING_CARRIER_ID]);

        self::applyName($shippingCarrier, $data);
        $this->applyDomesticClasses($shippingCarrier, $data);
        $this->applyInternationalClasses($shippingCarrier, $data);

        return $shippingCarrier;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyDomesticClasses(ShippingCarrier $shippingCarrier, array $data): void
    {
        if (empty($data[self::KEY_DOMESTIC_CLASSES])) {
            return;
        }
        if (!is_array($data[self::KEY_DOMESTIC_CLASSES])) {
            return;
        }
        $shippingCarrier->setDomesticClasses($this->shippingCarrierMailClassesTransformer->transform($data[self::KEY_DOMESTIC_CLASSES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyInternationalClasses(ShippingCarrier $shippingCarrier, array $data): void
    {
        if (empty($data[self::KEY_INTERNATIONAL_CLASSES])) {
            return;
        }
        if (!is_array($data[self::KEY_INTERNATIONAL_CLASSES])) {
            return;
        }
        $shippingCarrier->setInternationalClasses($this->shippingCarrierMailClassesTransformer->transform($data[self::KEY_INTERNATIONAL_CLASSES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyName(ShippingCarrier $shippingCarrier, array $data): void
    {
        if (empty($data[self::KEY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_NAME])) {
            return;
        }
        $shippingCarrier->setName($data[self::KEY_NAME]);
    }
}
