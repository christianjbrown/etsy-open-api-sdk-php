<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function is_array;

final class ListingWithAssociationsMediaFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    private ListingImagesTransformerInterface $listingImagesTransformer;
    private ListingVideosTransformerInterface $listingVideosTransformer;

    public function __construct(ListingImagesTransformerInterface $listingImagesTransformer, ListingVideosTransformerInterface $listingVideosTransformer)
    {
        $this->listingImagesTransformer = $listingImagesTransformer;
        $this->listingVideosTransformer = $listingVideosTransformer;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        $this->applyImages($listing, $data);
        $this->applyVideos($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyImages(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_IMAGES])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_IMAGES])) {
            return;
        }
        $listing->setImages($this->listingImagesTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_IMAGES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyVideos(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_VIDEOS])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_VIDEOS])) {
            return;
        }
        $listing->setVideos($this->listingVideosTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_VIDEOS]));
    }
}
