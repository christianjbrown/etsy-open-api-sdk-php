<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\BuyerTaxonomyNodeInterface;

interface BuyerTaxonomyNodeTransformerInterface
{
    public const string KEY_CHILDREN = 'children';
    public const string KEY_FULL_PATH_TAXONOMY_IDS = 'full_path_taxonomy_ids';
    public const string KEY_ID = 'id';
    public const string KEY_LEVEL = 'level';
    public const string KEY_NAME = 'name';
    public const string KEY_PARENT_ID = 'parent_id';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): BuyerTaxonomyNodeInterface;
}
