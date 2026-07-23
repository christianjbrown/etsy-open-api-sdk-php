<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryProductInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ListingInventoryProductsTransformer implements ListingInventoryProductsTransformerInterface
{
    private ListingInventoryProductTransformerInterface $listingInventoryProductTransformer;

    public function __construct(ListingInventoryProductTransformerInterface $listingInventoryProductTransformer)
    {
        $this->listingInventoryProductTransformer = $listingInventoryProductTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingInventoryProductInterface>
     */
    public function transform(array $data): array
    {
        $products = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $productData = $values[$i];
            if (!is_array($productData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $products[] = $this->listingInventoryProductTransformer->transform($productData);
        }

        return $products;
    }
}
