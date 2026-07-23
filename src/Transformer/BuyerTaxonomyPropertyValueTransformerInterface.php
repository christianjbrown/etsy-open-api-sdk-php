<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyValueInterface;

interface BuyerTaxonomyPropertyValueTransformerInterface
{
    public const string KEY_EQUAL_TO = 'equal_to';
    public const string KEY_NAME = 'name';
    public const string KEY_SCALE_ID = 'scale_id';
    public const string KEY_VALUE_ID = 'value_id';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): BuyerTaxonomyPropertyValueInterface;
}
