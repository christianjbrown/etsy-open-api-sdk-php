<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PaymentInterface;

interface PaymentsTransformerInterface
{
    public const string ARRAY_NAME = 'payment';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, PaymentInterface>
     */
    public function transform(array $data): array;
}
