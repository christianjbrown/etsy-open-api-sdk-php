<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TaxonomyNodePropertyInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class TaxonomyNodePropertiesTransformer implements TaxonomyNodePropertiesTransformerInterface
{
    private TaxonomyNodePropertyTransformerInterface $taxonomyNodePropertyTransformer;

    public function __construct(TaxonomyNodePropertyTransformerInterface $taxonomyNodePropertyTransformer)
    {
        $this->taxonomyNodePropertyTransformer = $taxonomyNodePropertyTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, TaxonomyNodePropertyInterface>
     */
    public function transform(array $data): array
    {
        $properties = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $propertyData = $values[$i];
            if (!is_array($propertyData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $properties[] = $this->taxonomyNodePropertyTransformer->transform($propertyData);
        }

        return $properties;
    }
}
