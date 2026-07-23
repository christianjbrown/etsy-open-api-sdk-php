<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodeInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class BuyerTaxonomyNodesTransformer implements BuyerTaxonomyNodesTransformerInterface
{
    private BuyerTaxonomyNodeTransformerInterface $buyerTaxonomyNodeTransformer;

    public function __construct(BuyerTaxonomyNodeTransformerInterface $buyerTaxonomyNodeTransformer)
    {
        $this->buyerTaxonomyNodeTransformer = $buyerTaxonomyNodeTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, BuyerTaxonomyNodeInterface>
     */
    public function transform(array $data): array
    {
        $nodes = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $nodeData = $values[$i];
            if (!is_array($nodeData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $nodes[] = $this->buyerTaxonomyNodeTransformer->transform($nodeData);
        }

        return $nodes;
    }
}
