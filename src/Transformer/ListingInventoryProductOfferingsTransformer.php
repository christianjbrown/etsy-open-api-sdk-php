<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ListingInventoryProductOfferingsTransformer implements ListingInventoryProductOfferingsTransformerInterface
{
    private ListingInventoryProductOfferingTransformerInterface $listingInventoryProductOfferingTransformer;

    public function __construct(ListingInventoryProductOfferingTransformerInterface $listingInventoryProductOfferingTransformer)
    {
        $this->listingInventoryProductOfferingTransformer = $listingInventoryProductOfferingTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingInventoryProductOfferingInterface>
     */
    public function transform(array $data): array
    {
        $offerings = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $offeringData = $values[$i];
            if (!is_array($offeringData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $offerings[] = $this->listingInventoryProductOfferingTransformer->transform($offeringData);
        }

        return $offerings;
    }
}
