<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\TaxonomyNodePropertyInterface;

interface TaxonomyNodePropertiesTransformerInterface
{
    public const string ARRAY_NAME = 'taxonomy_node_property';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, TaxonomyNodePropertyInterface>
     */
    public function transform(array $data): array;
}
