<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyScaleInterface;

interface BuyerTaxonomyPropertyScalesTransformerInterface
{
    public const string ARRAY_NAME = 'buyer_taxonomy_property_scale';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, BuyerTaxonomyPropertyScaleInterface>
     */
    public function transform(array $data): array;
}
