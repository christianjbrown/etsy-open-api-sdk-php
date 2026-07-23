<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingVariationImageInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ListingVariationImagesTransformer implements ListingVariationImagesTransformerInterface
{
    private ListingVariationImageTransformerInterface $listingVariationImageTransformer;

    public function __construct(ListingVariationImageTransformerInterface $listingVariationImageTransformer)
    {
        $this->listingVariationImageTransformer = $listingVariationImageTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingVariationImageInterface>
     */
    public function transform(array $data): array
    {
        $variationImages = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $variationImageData = $values[$i];
            if (!is_array($variationImageData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $variationImages[] = $this->listingVariationImageTransformer->transform($variationImageData);
        }

        return $variationImages;
    }
}
