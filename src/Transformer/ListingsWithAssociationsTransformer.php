<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ListingsWithAssociationsTransformer implements ListingsWithAssociationsTransformerInterface
{
    private ListingWithAssociationsTransformerInterface $listingWithAssociationsTransformer;

    public function __construct(ListingWithAssociationsTransformerInterface $listingWithAssociationsTransformer)
    {
        $this->listingWithAssociationsTransformer = $listingWithAssociationsTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingWithAssociationsInterface>
     */
    public function transform(array $data): array
    {
        $listings = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $listingData = $values[$i];
            if (!is_array($listingData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $listings[] = $this->listingWithAssociationsTransformer->transform($listingData);
        }

        return $listings;
    }
}
