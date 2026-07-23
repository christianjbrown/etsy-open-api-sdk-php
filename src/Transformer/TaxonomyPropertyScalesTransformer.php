<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TaxonomyPropertyScaleInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class TaxonomyPropertyScalesTransformer implements TaxonomyPropertyScalesTransformerInterface
{
    private TaxonomyPropertyScaleTransformerInterface $taxonomyPropertyScaleTransformer;

    public function __construct(TaxonomyPropertyScaleTransformerInterface $taxonomyPropertyScaleTransformer)
    {
        $this->taxonomyPropertyScaleTransformer = $taxonomyPropertyScaleTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, TaxonomyPropertyScaleInterface>
     */
    public function transform(array $data): array
    {
        $scales = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $scaleData = $values[$i];
            if (!is_array($scaleData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $scales[] = $this->taxonomyPropertyScaleTransformer->transform($scaleData);
        }

        return $scales;
    }
}
