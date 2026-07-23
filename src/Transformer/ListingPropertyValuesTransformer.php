<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingPropertyValueInterface;

use function array_values;
use function count;
use function sprintf;

final class ListingPropertyValuesTransformer implements ListingPropertyValuesTransformerInterface
{
    private ListingPropertyValueTransformerInterface $listingPropertyValueTransformer;

    public function __construct(ListingPropertyValueTransformerInterface $listingPropertyValueTransformer)
    {
        $this->listingPropertyValueTransformer = $listingPropertyValueTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingPropertyValueInterface>
     */
    public function transform(array $data): array
    {
        $propertyValues = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $propertyValueData = $values[$i];
            if (!is_array($propertyValueData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $propertyValues[] = $this->listingPropertyValueTransformer->transform($propertyValueData);
        }

        return $propertyValues;
    }
}
