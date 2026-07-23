<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TaxonomyPropertyValueInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class TaxonomyPropertyValuesTransformer implements TaxonomyPropertyValuesTransformerInterface
{
    private TaxonomyPropertyValueTransformerInterface $taxonomyPropertyValueTransformer;

    public function __construct(TaxonomyPropertyValueTransformerInterface $taxonomyPropertyValueTransformer)
    {
        $this->taxonomyPropertyValueTransformer = $taxonomyPropertyValueTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, TaxonomyPropertyValueInterface>
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
            $propertyValues[] = $this->taxonomyPropertyValueTransformer->transform($propertyValueData);
        }

        return $propertyValues;
    }
}
