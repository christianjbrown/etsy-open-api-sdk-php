<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ListingsTransformer implements ListingsTransformerInterface
{
    private ListingTransformerInterface $listingTransformer;

    public function __construct(ListingTransformerInterface $listingTransformer)
    {
        $this->listingTransformer = $listingTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingInterface>
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
            $listings[] = $this->listingTransformer->transform($listingData);
        }

        return $listings;
    }
}
