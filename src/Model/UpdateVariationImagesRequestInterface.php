<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `updateVariationImages` call.
 */
interface UpdateVariationImagesRequestInterface
{
    /**
     * @return array<int, ListingVariationImageRequestInterface>
     */
    public function getVariationImages(): array;

    /**
     * @param array<int, ListingVariationImageRequestInterface> $value
     */
    public function setVariationImages(array $value): self;
}
