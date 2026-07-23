<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\TransactionVariationInterface;

interface TransactionVariationTransformerInterface
{
    public const string KEY_FORMATTED_NAME = 'formatted_name';
    public const string KEY_FORMATTED_VALUE = 'formatted_value';
    public const string KEY_PROPERTY_ID = 'property_id';
    public const string KEY_QUESTION_ID = 'question_id';
    public const string KEY_VALUE_ID = 'value_id';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): TransactionVariationInterface;
}
