<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\MoneyInterface;

interface MoneyTransformerInterface
{
    public const string KEY_AMOUNT = 'amount';
    public const string KEY_CURRENCY_CODE = 'currency_code';
    public const string KEY_DIVISOR = 'divisor';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): MoneyInterface;
}
