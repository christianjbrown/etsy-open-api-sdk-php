<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingImageInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ListingImagesTransformer implements ListingImagesTransformerInterface
{
    private ListingImageTransformerInterface $listingImageTransformer;

    public function __construct(ListingImageTransformerInterface $listingImageTransformer)
    {
        $this->listingImageTransformer = $listingImageTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingImageInterface>
     */
    public function transform(array $data): array
    {
        $listingImages = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $listingImageData = $values[$i];
            if (!is_array($listingImageData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $listingImages[] = $this->listingImageTransformer->transform($listingImageData);
        }

        return $listingImages;
    }
}
