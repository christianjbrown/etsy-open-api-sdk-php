<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\SellerTaxonomyNodeInterface;

interface SellerTaxonomyNodesTransformerInterface
{
    public const string ARRAY_NAME = 'seller_taxonomy_node';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, SellerTaxonomyNodeInterface>
     */
    public function transform(array $data): array;
}
