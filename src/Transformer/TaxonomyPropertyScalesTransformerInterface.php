<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\TaxonomyPropertyScaleInterface;

interface TaxonomyPropertyScalesTransformerInterface
{
    public const string ARRAY_NAME = 'taxonomy_property_scale';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, TaxonomyPropertyScaleInterface>
     */
    public function transform(array $data): array;
}
