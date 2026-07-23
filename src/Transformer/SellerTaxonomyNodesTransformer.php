<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\SellerTaxonomyNodeInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class SellerTaxonomyNodesTransformer implements SellerTaxonomyNodesTransformerInterface
{
    private SellerTaxonomyNodeTransformerInterface $sellerTaxonomyNodeTransformer;

    public function __construct(SellerTaxonomyNodeTransformerInterface $sellerTaxonomyNodeTransformer)
    {
        $this->sellerTaxonomyNodeTransformer = $sellerTaxonomyNodeTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, SellerTaxonomyNodeInterface>
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
            $nodes[] = $this->sellerTaxonomyNodeTransformer->transform($nodeData);
        }

        return $nodes;
    }
}
