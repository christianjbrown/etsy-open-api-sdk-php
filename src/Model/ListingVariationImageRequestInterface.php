<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * One property-value-to-image binding in an `updateVariationImages` body.
 */
interface ListingVariationImageRequestInterface
{
    public function getImageId(): int;

    public function getPropertyId(): int;

    public function getValueId(): int;

    public function setImageId(int $value): self;

    public function setPropertyId(int $value): self;

    public function setValueId(int $value): self;
}
