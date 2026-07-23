<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNode;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodeInterface;

use function array_values;
use function count;
use function is_array;
use function is_int;
use function is_string;
use function sprintf;

final class BuyerTaxonomyNodeTransformer implements BuyerTaxonomyNodeTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): BuyerTaxonomyNodeInterface
    {
        if (!isset($data[self::KEY_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_ID));
        }
        if (!is_int($data[self::KEY_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_ID));
        }
        $node = new BuyerTaxonomyNode($data[self::KEY_ID]);

        $this->applyChildren($node, $data);
        self::applyFullPathTaxonomyIds($node, $data);
        self::applyLevel($node, $data);
        self::applyName($node, $data);
        self::applyParentId($node, $data);

        return $node;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyChildren(BuyerTaxonomyNode $node, array $data): void
    {
        if (!isset($data[self::KEY_CHILDREN])) {
            return;
        }
        if (!is_array($data[self::KEY_CHILDREN])) {
            return;
        }
        $children = [];
        $values = array_values($data[self::KEY_CHILDREN]);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $child = $values[$i];
            if (!is_array($child)) {
                continue;
            }
            $children[] = $this->transform($child);
        }
        $node->setChildren($children);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFullPathTaxonomyIds(BuyerTaxonomyNode $node, array $data): void
    {
        if (!isset($data[self::KEY_FULL_PATH_TAXONOMY_IDS])) {
            return;
        }
        if (!is_array($data[self::KEY_FULL_PATH_TAXONOMY_IDS])) {
            return;
        }
        $ids = [];
        $values = array_values($data[self::KEY_FULL_PATH_TAXONOMY_IDS]);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $id = $values[$i];
            if (!is_int($id)) {
                continue;
            }
            $ids[] = $id;
        }
        $node->setFullPathTaxonomyIds($ids);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLevel(BuyerTaxonomyNode $node, array $data): void
    {
        if (!isset($data[self::KEY_LEVEL])) {
            return;
        }
        if (!is_int($data[self::KEY_LEVEL])) {
            return;
        }
        $node->setLevel($data[self::KEY_LEVEL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyName(BuyerTaxonomyNode $node, array $data): void
    {
        if (empty($data[self::KEY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_NAME])) {
            return;
        }
        $node->setName($data[self::KEY_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyParentId(BuyerTaxonomyNode $node, array $data): void
    {
        if (!isset($data[self::KEY_PARENT_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_PARENT_ID])) {
            return;
        }
        $node->setParentId($data[self::KEY_PARENT_ID]);
    }
}
