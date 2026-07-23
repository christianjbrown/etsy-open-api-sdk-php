<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingVideoInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ListingVideosTransformer implements ListingVideosTransformerInterface
{
    private ListingVideoTransformerInterface $listingVideoTransformer;

    public function __construct(ListingVideoTransformerInterface $listingVideoTransformer)
    {
        $this->listingVideoTransformer = $listingVideoTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ListingVideoInterface>
     */
    public function transform(array $data): array
    {
        $listingVideos = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $listingVideoData = $values[$i];
            if (!is_array($listingVideoData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $listingVideos[] = $this->listingVideoTransformer->transform($listingVideoData);
        }

        return $listingVideos;
    }
}
