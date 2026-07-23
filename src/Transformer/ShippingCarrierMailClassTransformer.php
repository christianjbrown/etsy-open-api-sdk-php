<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShippingCarrierMailClass;
use ChristianBrown\Etsy\Model\ShippingCarrierMailClassInterface;

use function is_string;

final class ShippingCarrierMailClassTransformer implements ShippingCarrierMailClassTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShippingCarrierMailClassInterface
    {
        $mailClass = new ShippingCarrierMailClass();

        self::applyMailClassKey($mailClass, $data);
        self::applyName($mailClass, $data);

        return $mailClass;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMailClassKey(ShippingCarrierMailClass $mailClass, array $data): void
    {
        if (empty($data[self::KEY_MAIL_CLASS_KEY])) {
            return;
        }
        if (!is_string($data[self::KEY_MAIL_CLASS_KEY])) {
            return;
        }
        $mailClass->setMailClassKey($data[self::KEY_MAIL_CLASS_KEY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyName(ShippingCarrierMailClass $mailClass, array $data): void
    {
        if (empty($data[self::KEY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_NAME])) {
            return;
        }
        $mailClass->setName($data[self::KEY_NAME]);
    }
}
