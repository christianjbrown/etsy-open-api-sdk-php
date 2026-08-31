<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateVariationImagesRequest implements UpdateVariationImagesRequestInterface
{
    /**
     * @var array<int, ListingVariationImageRequestInterface>
     */
    private array $variationImages = [];

    /**
     * @param array<int, ListingVariationImageRequestInterface> $variationImages
     */
    public function __construct(array $variationImages)
    {
        $this->variationImages = $variationImages;
    }

    /**
     * @return array<int, ListingVariationImageRequestInterface>
     */
    public function getVariationImages(): array
    {
        return $this->variationImages;
    }

    /**
     * @param array<int, ListingVariationImageRequestInterface> $value
     */
    public function setVariationImages(array $value): UpdateVariationImagesRequestInterface
    {
        $this->variationImages = $value;

        return $this;
    }
}
