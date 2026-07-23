<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShippingCarrierMailClassInterface;

interface ShippingCarrierMailClassTransformerInterface
{
    public const string KEY_MAIL_CLASS_KEY = 'mail_class_key';
    public const string KEY_NAME = 'name';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShippingCarrierMailClassInterface;
}
